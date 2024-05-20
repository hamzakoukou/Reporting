<?php include 'layout/header.php'; ?>
    

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Header -->
                <div class="content bg-image overflow-hidden" style="background:#a32034;">
                    <div class="push-50-t push-15">
                        <h1 class="h2 text-white animated zoomIn">Dashboard</h1>
                        <h2 class="h5 text-white-op animated zoomIn">Welcome Administrator</h2>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Stats -->
                <div class="content bg-white border-b">
                    <div class="row items-push text-uppercase">
                        <div class="col-xs-6 col-sm-3">
                            <div class="font-w700 text-gray-darker animated fadeIn">Product Sales</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Today</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html">300</a>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <div class="font-w700 text-gray-darker animated fadeIn">Product Sales</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> This Month</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html">8,790</a>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <div class="font-w700 text-gray-darker animated fadeIn">Total Earnings</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> All Time</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html">$ 93,880</a>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <div class="font-w700 text-gray-darker animated fadeIn">Average Sale</div>
                            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> All Time</small></div>
                            <a class="h2 font-w300 text-primary animated flipInX" href="base_comp_charts.html">$ 270</a>
                        </div>
                    </div>
                </div>
                <!-- END Stats -->

                <!-- Page Content -->
                <div class="content">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Main Dashboard Chart -->
                            <div class="block">
                                <div class="block-header">
                                    <ul class="block-options">
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">Weekly Overview</h3>
                                </div>
                                <div class="block-content block-content-full bg-gray-lighter text-center">
                                    <!-- Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/ -->
                                    <div style="height: 374px;"><canvas class="js-dash-chartjs-lines"></canvas></div>
                                </div>
                                <div class="block-content text-center">
                                    <div class="row items-push text-center">
                                        <div class="col-xs-6 col-lg-3">
                                            <div class="push-10"><i class="si si-graph fa-2x"></i></div>
                                            <div class="h5 font-w300 text-muted">+ 205 Sales</div>
                                        </div>
                                        <div class="col-xs-6 col-lg-3">
                                            <div class="push-10"><i class="si si-users fa-2x"></i></div>
                                            <div class="h5 font-w300 text-muted">+ 25% Clients</div>
                                        </div>
                                        <div class="col-xs-6 col-lg-3 visible-lg">
                                            <div class="push-10"><i class="si si-star fa-2x"></i></div>
                                            <div class="h5 font-w300 text-muted">+ 10 Ratings</div>
                                        </div>
                                        <div class="col-xs-6 col-lg-3 visible-lg">
                                            <div class="push-10"><i class="si si-share fa-2x"></i></div>
                                            <div class="h5 font-w300 text-muted">+ 35 Followers</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Main Dashboard Chart -->
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
                <div class="pull-left">
                    <a class="font-w600" href="javascript:void(0)" target="_blank">Linedata</a> &copy; <span class="js-year-copy"></span>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

      

        <!-- Linedata Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        <script src="assets/js/core/jquery.slimscroll.min.js"></script>
        <script src="assets/js/core/jquery.scrollLock.min.js"></script>
        <script src="assets/js/core/jquery.appear.min.js"></script>
        <script src="assets/js/core/jquery.countTo.min.js"></script>
        <script src="assets/js/core/jquery.placeholder.min.js"></script>
        <script src="assets/js/core/js.cookie.min.js"></script>
        <script src="assets/js/app.js"></script>

        <!-- Page Plugins -->
        <script src="assets/js/plugins/slick/slick.min.js"></script>
        <script src="assets/js/plugins/chartjs/Chart.min.js"></script>

        <!-- Page JS Code -->
        <script src="assets/js/pages/base_pages_dashboard.js"></script>
        <script>
            $(function () {
                // Init page helpers (Slick Slider plugin)
                App.initHelpers('slick');
            });
        </script>
    </body>
</html>
