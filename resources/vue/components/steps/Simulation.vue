<template>
    <h3>Simulation</h3>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <button class="btn btn-primary" @click="state.currentWeek--" :disabled="state.currentWeek === 0">Previous</button>
                        <div>Week {{ state.currentWeek }} / {{ fixtures.length }}</div>
                        <button class="btn btn-primary" @click="state.currentWeek++" :disabled="state.currentWeek === state.scoreTables.length - 1">Next</button>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Team name</th>
                            <th>Played</th>
                            <th>Won</th>
                            <th>Drawn</th>
                            <th>Lost</th>
                            <th>Points</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(team, i) in league.teams">
                            <td>{{ team.name }}</td>
                            <td>{{ +state.scoreTables[state.currentWeek][i].won + +state.scoreTables[state.currentWeek][i].drawn + +state.scoreTables[state.currentWeek][i].lost }}</td>
                            <td><input v-model="state.scoreTables[state.currentWeek][i].won" type="number" min="0" class="stealth-input" /></td>
                            <td><input v-model="state.scoreTables[state.currentWeek][i].drawn" type="number" min="0" class="stealth-input" /></td>
                            <td><input v-model="state.scoreTables[state.currentWeek][i].lost" type="number" min="0" class="stealth-input" /></td>
                            <td>{{ +state.scoreTables[state.currentWeek][i].won * 3 + +state.scoreTables[state.currentWeek][i].drawn }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" v-if="fixtures[state.currentWeek]">
                <div class="card-header">
                    Next week {{ state.currentWeek + 1 }}
                </div>
                <div class="card-body">
                    <VersusTable :fixture="fixtures[state.currentWeek]" :league="league" />
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Championship predictions</th>
                            <th>%</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(team, i) in league.teams">
                            <td>{{ team.name }}</td>
                            <td>{{ Math.round(state.prediction[i] * 10000) / 100 }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-sm btn-outline-primary" @click="predict">Recalculate</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <div><button :disabled="state.submitting" class="btn btn-primary" @click="reset">Reset</button></div>
        <div><button :disabled="state.submitting" class="btn btn-primary" @click="playAllWeeks">Play all weeks</button></div>
        <div><button :disabled="state.submitting" class="btn btn-primary" @click="playNextWeek">Play next week</button></div>
    </div>
</template>

<script>
import VersusTable from "../../layout/VersusTable";
import lodash from 'lodash';
export default {
    name: "Simulation",
    components: {VersusTable},
    props: {
        league: {
            type: Object,
            required: true,
            description: "League teams"
        },
        fixtures: {
            type: Array,
            required: true,
            description: "Generated fixtures"
        }
    },
    data() {
        return {
            state: {
                submitting: false,
                currentWeek: 0,
                scoreTables: [],
            }
        }
    },
    watch: {
        async 'state.currentWeek'() {
            await this.predict();
        },
    },
    created() {
        this.reset();
    },
    methods: {
        async playNextWeek() {
            if (this.state.currentWeek >= this.fixtures.length) {
                return;
            }
            this.state.submitting = true;
            let fixture = this.fixtures[this.state.currentWeek];
            let result = await axios.post('/api/simulation', {
                league: this.league,
                fixture: fixture,
            });
            let prevWeek = this.state.currentWeek;
            this.state.currentWeek++;
            let curWeek  = this.state.currentWeek;
            this.state.scoreTables[curWeek] = lodash.cloneDeep(this.state.scoreTables[prevWeek]);

            result = result.data.response;
            result.forEach((match, i) => {
                match.forEach((teamResult, j) => {
                    let team = fixture[i][j];
                    if (teamResult === 1) {
                        this.state.scoreTables[curWeek][team].won++;
                    } else if (teamResult === -1) {
                        this.state.scoreTables[curWeek][team].lost++;
                    } else {
                        this.state.scoreTables[curWeek][team].drawn++;
                    }
                });
            });


            this.state.submitting = false;
        },
        reset() {
            this.state.submitting = false;
            this.state.currentWeek = 0;
            this.state.scoreTables = [];
            this.state.prediction = this.league.teams.map(_ => 0);
            this.state.scoreTables.push(this.league.teams.map(_ => ({
                won: 0,
                drawn: 0,
                lost: 0
            })));
            this.predict();

        },
        async playAllWeeks() {
            while (this.state.currentWeek < this.fixtures.length) {
                await this.playNextWeek();
            }
        },
        async predict()
        {
            let result = await axios.post('/api/predict', {
                fixtureCount: this.fixtures.length - this.state.currentWeek,
                scoreTable: this.state.scoreTables[this.state.currentWeek],
            });

            this.state.prediction = result.data.response;
        }
    }
}
</script>

<style scoped>

</style>
