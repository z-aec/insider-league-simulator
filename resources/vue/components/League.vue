<template>
    <TeamSelect v-if="currentStep === 0" @submitted="onTeamSelect" />
    <GenerateFixtures v-else-if="currentStep === 1" :league="league" @submitted="onStartSimulation" />
    <Simulation v-else-if="currentStep === 2" :league="league" :fixtures="fixtures" />
</template>

<script>
import TeamSelect from "./steps/TeamSelect";
import GenerateFixtures from "./steps/GenerateFixtures";
import Simulation from "./steps/Simulation";
export default {
    name: "League",
    components: {Simulation, GenerateFixtures, TeamSelect},
    data() {
        return {
            currentStep: 0,
            league: null,
            fixtures: null,
        }
    },
    methods: {
        nextStep() {
            this.currentStep++;
        },
        onTeamSelect(data) {
            this.league = data;
            this.nextStep();
        },
        onStartSimulation(data) {
            this.fixtures = data;
            this.nextStep();
        }
    }
}
</script>

<style scoped>

</style>
