<template>
    <div>
        <div v-if="state.loading">Loading...</div>
        <div v-else class="row">
            <h3>Fixtures</h3>
            <div v-for="(fixture, week) in fixtures" class="col-3 mb-1">
                <div class="card">
                    <div class="card-header">
                        Week {{ week + 1 }}
                    </div>
                    <div class="card-body">
                        <VersusTable :fixture="fixture" :league="league" />
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-block m-1" @click.prevent="submit">Start simulation</button>
        </div>
    </div>
</template>

<script>
import VersusTable from "../../layout/VersusTable";
export default {
    name: "GenerateFixtures",
    components: {VersusTable},
    emits: ['submitted'],
    props: {
        league: {
            type: Object,
            required: true,
            description: "League teams"
        }
    },
    data() {
        return {
            state: {
                loading: true,
            },
            fixtures: null,
        }
    },
    computed: {
        teamsCount() {
            return this.league.teams.length;
        },
    },
    async created() {
        let data = await axios.get('/api/fixtures/generate', {params: {teams: this.teamsCount}});
        this.fixtures = data.data.response;
        this.state.loading = false;
    },
    methods: {
        submit() {
            this.$emit('submitted', this.fixtures);
        }
    }
}
</script>

<style scoped>

</style>
