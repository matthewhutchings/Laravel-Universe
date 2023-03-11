<script type="text/ecmascript-6">
import Chart from './chart.vue'
import axios from 'axios';
export default {
    components: { Chart },
    data() {
        return {
            dashboard: []
        }
    },

    watch: {
        $route(to, from) {
            this.dashboard = {};
            this.getDashboardData()
        }
    },

    mounted() {
        this.getDashboardData();
        document.title = "Dashboards - Universe";
    },
    methods: {
        getDashboardData() {
            axios.get(Telescope.basePath + '/telescope-api/dashboards/' + this.slug).then(response => {
                this.dashboard = response.data;

                this.dashboard.views.forEach(function (item, index, arr) {

                    console.log(item)
                    console.log(index)
                    console.log(arr)

                })

            })
        }
    },
    computed: {
        slug() {
            return this.$route.params.slug;
        },
    },
}
</script>

<template>
    <div class="card overflow-hidden">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="h6 m-0">{{ this.dashboard.name }}</h2>
        </div>
        <div class="card-body text-center card-bg-secondary py-2">
            <chart v-bind:data="[
                Math.random(),
                Math.random(),
                Math.random(),
                Math.random(),
                Math.random(),
                Math.random()
            ]"></chart>
        </div>
    </div>
</template>
