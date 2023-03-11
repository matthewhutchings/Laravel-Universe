<!DOCTYPE html>
<html lang="en">

<head>
      <!-- Meta Information -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" href="{{ asset('/vendor/telescope/favicon.ico') }}">

      <meta name="robots" content="noindex, nofollow">

      <title>Universe{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

      <!-- Style sheets-->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600" rel="stylesheet" />
      <link href="{{ asset(mix($cssFile, 'vendor/telescope')) }}" rel="stylesheet" type="text/css">
      <script src="/vendor/telescope/d3-3-5-5.min.js"></script>

</head>

<body>
      <div id="telescope" v-cloak>
            <alert :message="alert.message" :type="alert.type" :auto-close="alert.autoClose" :confirmation-proceed="alert.confirmationProceed" :confirmation-cancel="alert.confirmationCancel" v-if="alert.type"></alert>

            <div class="container mb-5">
                  <div class="d-flex align-items-stretch py-4 header">
                        <router-link to="/" class="logo d-flex align-items-center">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                                    <path class="fill-primary" d="M0 40a39.87 39.87 0 0 1 11.72-28.28A40 40 0 1 1 0 40zm34 10a4 4 0 0 1-4-4v-2a2 2 0 1 0-4 0v2a4 4 0 0 1-4 4h-2a2 2 0 1 0 0 4h2a4 4 0 0 1 4 4v2a2 2 0 1 0 4 0v-2a4 4 0 0 1 4-4h2a2 2 0 1 0 0-4h-2zm24-24a6 6 0 0 1-6-6v-3a3 3 0 0 0-6 0v3a6 6 0 0 1-6 6h-3a3 3 0 0 0 0 6h3a6 6 0 0 1 6 6v3a3 3 0 0 0 6 0v-3a6 6 0 0 1 6-6h3a3 3 0 0 0 0-6h-3zm-4 36a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM21 28a3 3 0 1 0 0-6 3 3 0 0 0 0 6z">
                                    </path>
                              </svg>

                              <h4 class="mb-0 ml-3"><strong>Laravel</strong>
                                    Universe{{ config('app.name') ? ' - ' . config('app.name') : '' }}</h4>
                        </router-link>

                        <button class="btn btn-muted ml-auto mr-3 d-flex align-items-center py-2" v-on:click.prevent="toggleRecording" :title="recording ? 'Pause recording' : 'Resume recording'">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon" fill="currentColor" v-if="recording">
                                    <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z" />
                              </svg>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon" fill="currentColor" v-else>
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                              </svg>
                        </button>

                        <button class="btn btn-muted mr-3 d-flex align-items-center py-2" v-on:click.prevent="clearEntries" title="Clear entries">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                              </svg>
                        </button>

                        <button class="btn btn-muted mr-3 d-flex align-items-center py-2" :class="{active: autoLoadsNewEntries}" v-on:click.prevent="autoLoadNewEntries" title="Auto load entries">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon" fill="currentColor">
                                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                              </svg>
                        </button>

                        <router-link to="/monitored-tags" class="btn btn-muted d-flex align-items-center py-2" title="Monitoring">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon" fill="currentColor">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                              </svg>
                        </router-link>
                  </div>

                  <div class="row mt-4">
                        <div class="col-2 sidebar">
                              <ul class="nav flex-column">

                                    <li class="nav-item">
                                          <router-link active-class="active" to="/overview" class="nav-link d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                      <path fill-rule="evenodd" d="M3.25 3A2.25 2.25 0 001 5.25v9.5A2.25 2.25 0 003.25 17h13.5A2.25 2.25 0 0019 14.75v-9.5A2.25 2.25 0 0016.75 3H3.25zM2.5 9v5.75c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75V9h-15zM4 5.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H4zM6.25 6A.75.75 0 017 5.25h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H7a.75.75 0 01-.75-.75V6zM10 5.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H10z" clip-rule="evenodd" />
                                                </svg>
                                                <span>Universe Explorer</span>
                                          </router-link>
                                    </li>

                                    @foreach($menu_items as $group => $key)
                                    <div class="nav-item mb-3 mt-3 text-muted">{{$group}} Universe</div>
                                    @foreach( $key as $item)
                                    <li class="nav-item">
                                          <router-link active-class="active" to="/dashboards/{{ $item->slug() }}" class="nav-link d-flex align-items-center">
                                                <span>{{ $item->name }} </span>
                                          </router-link>
                                    </li>
                                    @endforeach
                                    @endforeach
                                    <div class="nav-item mb-3 mt-5 text-muted">Manage</div>
                                    <li class="nav-item ">
                                          <a href="/nova/resources/universe-dashboards" class="nav-link d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                      <path d="M.96 9.84c1.35.61 6.83 2.83 7.73 3.26.93.44 1.58.45 2.75-.16 1.13-.59 6.21-2.68 7.6-3.36l.16.06c1.04.38 1.08.7.01 1.25-1.06.56-6.59 2.83-7.77 3.44-1.17.62-1.82.61-2.75.17-.93-.45-6.81-2.82-7.87-3.33-1.06-.5-1.08-.85-.04-1.26l.18-.07zM.8 13.19h.01c1.06.5 6.94 2.88 7.87 3.33.93.44 1.58.45 2.75-.17 1.17-.6 6.6-2.84 7.74-3.42h.02c1.04.39 1.08.7.01 1.26-1.06.55-6.59 2.82-7.77 3.44-1.17.61-1.82.6-2.75.16-.93-.44-6.81-2.82-7.87-3.33-1.06-.5-1.08-.85-.04-1.26l.03-.01zm18.4-5.71c-1.06.55-6.59 2.82-7.77 3.44-1.17.61-1.82.6-2.75.16-.93-.44-6.81-2.82-7.87-3.32C-.24 7.25-.26 6.9.78 6.49c1.04-.4 6.89-2.7 8.12-3.14 1.24-.44 1.67-.46 2.72-.07 1.05.38 6.54 2.57 7.58 2.95 1.04.38 1.08.7.01 1.25zm-6.59-1.95l-1.34-.5.36-.86-1.32.44-1.4-.55.45.83-1.5.53 2 .18.63 1.04.39-.93 1.73-.18zm-2.22 4.53L11.8 8l-4.63.7 3.23 1.35zm-4.48-2.1c1.37 0 2.47-.42 2.47-.95s-1.1-.96-2.47-.96-2.48.43-2.48.96 1.11.96 2.48.96zm8.75-2.17v2.16l2.74-1.08-2.74-1.08zm-3.03 1.2l2.73 1.08.3-.12V5.8l-3.03 1.2z">
                                                      </path>
                                                </svg>
                                                <span>Dashboards</span>
                                          </a>
                                    </li>
                                    <li class="nav-item">
                                          <a href="/nova/resources/universe-views" class="nav-link d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                      <path fill-rule="evenodd" d="M3.25 3A2.25 2.25 0 001 5.25v9.5A2.25 2.25 0 003.25 17h13.5A2.25 2.25 0 0019 14.75v-9.5A2.25 2.25 0 0016.75 3H3.25zM2.5 9v5.75c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75V9h-15zM4 5.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H4zM6.25 6A.75.75 0 017 5.25h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H7a.75.75 0 01-.75-.75V6zM10 5.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H10z" clip-rule="evenodd" />
                                                </svg>
                                                <span>Views</span>
                                          </a>
                                    </li>
                              </ul>
                        </div>

                        <div class="col-10">
                              <router-view></router-view>
                        </div>
                  </div>
            </div>
      </div>

      <!-- Global Telescope Object -->
      <script>
            window.Telescope = @json($telescopeScriptVariables);
      </script>

      <script src="{{ asset(mix('app.js', 'vendor/telescope')) }}"></script>
</body>

</html>