<script type="text/ecmascript-6">
import Chart from './chart.vue'
import axios from 'axios';
export default {
      components: { Chart },
      data() {
            return {
                  dashboard: [],
                  submitting: null,
                  query: " all users who have signed up using Facebook as the source",
                  sql: "",
                  text: "",
                  model: "ExampleData",
                  preview: null,
                  gettingData: null,
                  resultdata: [],
                  columns: [],
                  colorBy: '',
                  groupBy: '',
                  singlePosition: { x: 800, y: 500, positionNumber: 1 },

            }
      },
      mounted() {
      },
      methods: {
            submit() {
                  this.submitting = 1;
                  this.count = null
                  axios.post(Telescope.basePath + '/telescope-api/overview', {
                        search: this.query,
                        model: this.model,
                  }).then(response => {
                        this.preview = response.data
                        this.sql = this.preview.query
                        this.submitting = null;
                        this.getData();

                  }).catch(function (error) {

                  })
                  setTimeout(() => {
                        //    this.scrollToElement()
                  }, 20);

            },
            getData() {
                  this.gettingData = 1
                  axios.post(Telescope.basePath + '/telescope-api/overview/get-data', {
                        sql: this.sql
                  }).then(response => {
                        this.resultdata = response.data.results
                        this.columns = response.data.columns
                        this.gettingData = null;

                  })
            },
            scrollToElement(myElement) {
                  const documentHeight = document.documentElement.scrollHeight;
                  const scrollToHeight = documentHeight * 0.4; // 20% of document height
                  window.scrollTo({
                        top: scrollToHeight,
                        behavior: 'smooth',
                  });
            },
            getRandomColor() {
                  const r = Math.floor(Math.random() * 256);
                  const g = Math.floor(Math.random() * 256);
                  const b = Math.floor(Math.random() * 256);
                  return "#" + r.toString(16) + g.toString(16) + b.toString(16);
            },
            retry() {
                  this.preview = null;
                  this.submitting = 1;
                  this.count = null
                  axios.post(Telescope.basePath + '/telescope-api/overview', {
                        search: this.query,
                        model: this.model,
                        sql: this.sql
                  }).then(response => {
                        this.preview = response.data
                        this.sql = this.preview.query
                        this.submitting = null;
                        this.getData();
                  })
            },
      },
      watch: {
            groupBy() {
                  this.drawChart();
            },
      },
      computed: {
            parsedData() {
                  if (this.groupedResults.length === 1) {
                        const position = this.singlePosition;
                        return [
                              {
                                    x: position.x,
                                    y: position.y,
                                    color: this.getRandomColor(),
                                    nodes: this.groupedResults[0].length,
                              },
                        ];
                  } else {
                        return this.groupedResults.map((group, i) => {
                              const positionIndex = i % 3;
                              const x = 200 + positionIndex * 500;
                              const y = Math.floor(i / 3) * 400 + 250;
                              return {
                                    x: x,
                                    y: y,
                                    color: this.getRandomColor(),
                                    nodes: group.length,
                                    name: group.name
                              };
                        });
                  }
            },
            groupedResults() {
                  return Object.values(this.resultdata.reduce(function (acc, obj) {
                        var key = obj[this.colorBy];
                        console.log(key)
                        if (!acc[key]) {
                              acc[key] = [];
                        }
                        acc[key].push({ ...obj, name: key });
                        return acc;
                  }.bind(this), {}));
            },
            slug() {
                  return this.$route.params.slug;
            },
      },
}
</script>

<template>
      <div class="">
            <div class="row mb-5">
                  <div class="col-lg-12 mx-auto">
                        <div class="card-header d-flex align-items-center justify-content-between card-bg-secondary">
                              <h2 class="h6 m-0  ">Step 1) What do you want to see...</h2>
                              <span class="btn btn-light">Guide</span>
                        </div>
                        <div class="bg-white p-5 rounded shadow">
                              <form action="">
                                    <div class="row no-gutters">
                                          <div class="col-sm col-sm-3 pr-2">
                                                <div class="form-group">
                                                      <select class="form-control" id="exampleFormControlSelect1"
                                                            v-model="model">
                                                            <option value="ExampleData">Example Data</option>
                                                            <option value="users">Users</option>
                                                            <option value="applies">Applications</option>
                                                            <option>Dealers</option>
                                                            <option>Brokers</option>
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="col-sm-9">
                                                <div>
                                                      <div class="input-group mb-4">
                                                            <input type="search" placeholder="What do you want to see..."
                                                                  aria-describedby="button-addon5" v-model="query"
                                                                  :disabled="this.submitting" class="form-control">
                                                            <div class="input-group-append">
                                                                  <button id="button-addon5" type="submit"
                                                                        class="btn btn-primary" :disabled="this.submitting"
                                                                        @click="submit()">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                              viewBox="0 0 20 20" class="icon "
                                                                              fill="currentColor"
                                                                              :class="{ rotating: this.submitting }">
                                                                              <path fill-rule="evenodd"
                                                                                    d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z"
                                                                                    clip-rule="evenodd" />
                                                                        </svg> </button>
                                                            </div>
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="col-sm-12" v-if="sql">
                                                <div class="input-group mb-4">
                                                      <input type="search" placeholder="What do you want to see..."
                                                            aria-describedby="button-addon5" v-model="sql"
                                                            :disabled="this.submitting" class="form-control">
                                                      <div class="input-group-append">
                                                            <button id="button-addon5" type="submit" class="btn btn-primary"
                                                                  :disabled="this.submitting" @click="retry()">
                                                                  Retry Query </button>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </form>
                        </div>
                        <div class="universe" v-if="preview" ref="myElement">
                              <chart :dataResults="parsedData"></chart>
                        </div>
                        <div class="col-12 p-0" v-if="preview">
                              <div
                                    class="card-header d-flex align-items-center justify-content-between card-bg-secondary mt-4">
                                    <h2 class="h6 m-0  mt-3">Results</h2>
                                    <span class="btn btn-light"> <a class="btn btn-primary" data-toggle="collapse"
                                                href="#collapseExample" role="button" aria-expanded="false"
                                                aria-controls="collapseExample">
                                                Show
                                          </a></span>
                              </div>
                              <div class="card collapse" id="collapseExample">
                                    <table class="table table-hover mb-0">
                                          <thead>
                                                <tr>
                                                      <th v-for="col in columns" :key="col">{{ col }}</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                <tr v-for="(item, index) in resultdata" :key="index">
                                                      <td v-for="column in columns" :key="column.key">
                                                            {{ item[column] }}
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                        </div>


                        <div class="col-12 p-0" v-if="preview">
                              <div
                                    class="card-header d-flex align-items-center justify-content-between card-bg-secondary mt-4">
                                    <h2 class="h6 m-0  mt-3">Data Grouping</h2>

                              </div>
                              <div class="bg-white p-5 rounded shadow">
                                    <div class="form-group row">
                                          <label for="staticEmail" class="col-sm-2 col-form-label">Group By</label>
                                          <div class="col-sm-10">
                                                <select class="form-control" id="exampleFormControlSelect1" v-model="colorBy">
                                                      <option value="">Select An Option</option>
                                                      <option v-for="col in columns" :key="col" :value="col">{{ col }}
                                                      </option>
                                                </select>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
@keyframes rotating {
      from {
            transform: rotate(0deg);
            -o-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
      }

      to {
            transform: rotate(360deg);
            -o-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
      }
}

@-webkit-keyframes rotating {
      from {
            transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
      }

      to {
            transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
      }
}

.rotating {
      -webkit-animation: rotating 1s linear infinite;
      -moz-animation: rotating 1s linear infinite;
      -ms-animation: rotating 1s linear infinite;
      -o-animation: rotating 1s linear infinite;
      animation: rotating 1s linear infinite;
}
</style>