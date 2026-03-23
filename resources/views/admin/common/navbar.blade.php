<header class="main-header">
    <!-- Logo -->
    <a class="logo" href="/">
        <span class="logo-mini"><b>G</b></span>
        <span class="logo-lg"><b>My App</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- Back Login Menu -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-repeat text-aqua"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><b>username</b></li>
                        <li>
                            <ul class="menu">
                                <li><a href="#">Back Login</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Shift Menu -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-time text-aqua"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><b>username</b></li>
                        <li>
                            <ul class="menu">
                                <li><a href="#" data-toggle="modal" data-target="#openShiftModal">Start Shift</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Profile / Logout Menu -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-off text-aqua"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><b>username</b></li>
                        <li>
                            <ul class="menu">
                                <li><a href="#">Active Sessions</a></li>
                                <li><a href="#">Edit Credit</a></li>
                                <li><a href="#">My Profile</a></li>
                                {{-- <li><a href="#">Logout</a></li> --}}
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit">Logout</button>
                                    </form>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

<!-- Open Shift Modal -->
<div class="modal fade" id="openShiftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="GET" id="outForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Start Shift</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Start:</td>
                            <td>2026-03-22 10:00 AM</td>
                        </tr>
                        <tr>
                            <td>Money:</td>
                            <td>5000</td>
                        </tr>
                        <tr>
                            <td>In:</td>
                            <td>3000</td>
                        </tr>
                        <tr>
                            <td>Out:</td>
                            <td>1000</td>
                        </tr>
                        <tr>
                            <td>Total:</td>
                            <td>2000</td>
                        </tr>
                        <tr>
                            <td>Transfers:</td>
                            <td>250</td>
                        </tr>
                        <tr>
                            <td>Pay Out:</td>
                            <td>33%</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <a href="#" target="_blank" class="btn btn-success">Print</a>
                    <button type="submit" class="btn btn-primary">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
