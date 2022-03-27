<template>
    <div>
        <form>
            <h3>Input teams' name and strength</h3>
            <div v-for="(_, i) in form.teams" class="input-group p-1">
                <input v-model="form.teams[i].name" class="form-control w-50" type="text" />
                <input v-model="form.teams[i].strength" class="form-control" type="number" min="0" />
                <button class="btn btn-danger" @click.prevent="removeTeam(i)">x</button>
            </div>
            <div class="m-1">
                <label>
                    <small>Additional strength at home matches:</small>
                    <input v-model="form.strengthAtHome" class="form-control" type="number" min="0" />
                </label>
            </div>
            <div class="d-flex justify-content-between">
            <button class="btn btn-outline-success m-1" @click.prevent="addTeam">Add team</button>
            <button class="btn btn-primary m-1 float-right" @click.prevent="submit">Generate fixtures</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "TeamSelect",
    emits: ['submitted'],
    data() {
        return {
            form: {
                teams: [
                    {name: "Liverpool", strength: 5},
                    {name: "Manchester City", strength: 3},
                    {name: "Chelsea", strength: 1},
                    {name: "Arsenal", strength: 4},
                ],
                strengthAtHome: 0
            }
        }
    },
    methods: {
        submit() {
            this.$emit('submitted', this.form);
        },
        addTeam() {
            this.form.teams.push({name: "Team " + (this.form.teams.length + 1), strength: 1})
        },
        removeTeam(index) {
            this.form.teams = this.form.teams.filter((_, i) => i !== index);
        }
    }
}
</script>

<style scoped>

</style>
