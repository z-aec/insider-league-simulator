<template>
    <table class="table table-borderless">
        <template v-for="match in fixture">
            <tr v-if="league.teams[match[0]] && league.teams[match[1]]">
                <td>{{ league.teams[match[0]].name }}</td>
                <td>-</td>
                <td>{{ league.teams[match[1]].name }}</td>
            </tr>
        </template>
    </table>
</template>

<script>
export default {
    name: "VersusTable",
    props: {
        fixture: {
            type: Object,
            required: true,
            description: "Fixture to render"
        },
        league: {
            type: Object,
            required: true,
            description: "League data (teams' name, etc)"
        }
    }
}
</script>

<style scoped>

</style>
