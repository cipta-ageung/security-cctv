<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-background"></div>
    <div class="sidebar-wrapper scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="photo">
                    <img src="{{ asset('admin/images/').'/'.Auth::user()->avatar }}" alt="image profile">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Auth::user()->name }}
                            <span class="user-level">Administrator</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('admin.index') }}">
                                    <span class="link-collapse">Kelola Admin</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav">
                <li class="nav-item">
                    <a href="{{ route('home') }}">
                        <i class="flaticon-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Kelola</h4>
                </li>
                <li class="nav-item {{ Route::is('news.index') ? 'active' : '' }}{{ Route::is('news.create') ? 'active' : '' }}{{ Route::is('news.edit') ? 'active' : '' }}">
                    <a href="{{ route('news.index') }}">
                        <i class="flaticon-pencil"></i>
                        <p>Berita Terbaru</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('reminder-installment.index') ? 'active' : '' }}{{ Route::is('reminder-installment.create') ? 'active' : '' }}{{ Route::is('reminder-ipl.index') ? 'active' : '' }}{{ Route::is('reminder-ipl.create') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#forms">
                        <i class="flaticon-coins"></i>
                        <p>Pembayaran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Route::is('reminder-installment.index') ? 'show' : '' }}{{ Route::is('reminder-installment.create') ? 'show' : '' }}{{ Route::is('reminder-ipl.index') ? 'show' : '' }}{{ Route::is('reminder-ipl.create') ? 'show' : '' }}" id="forms">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::is('reminder-installment.index') ? 'active' : '' }}{{ Route::is('reminder-installment.create') ? 'active' : '' }}">
                                <a href="{{ route('reminder-installment.index') }}">
                                    <span class="sub-item">Pengingat Cicilan</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('reminder-ipl.index') ? 'active' : '' }}{{ Route::is('reminder-ipl.create') ? 'active' : '' }}">
                                <a href="{{ route('reminder-ipl.index') }}">
                                    <span class="sub-item">Pengingat Iuran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Route::is('occupant.index') ? 'active' : '' }}{{ Route::is('occupant.create') ? 'active' : '' }}{{ Route::is('occupant.edit') ? 'active' : '' }}"">
                    <a href="{{ route('occupant.index') }}">
                        <i class="flaticon-user-2"></i>
                        <p>Penghuni</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('jualan.index') ? 'active' : '' }}{{ Route::is('jualan.edit') ? 'active' : '' }}">
                    <a href="{{ route('jualan.index') }}">
                        <i class="flaticon-shopping-bag"></i>
                        <p>Jualan</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('contact.index') ? 'active' : '' }}{{ Route::is('contact.create') ? 'active' : '' }}{{ Route::is('contact.edit') ? 'active' : '' }}">
                    <a href="{{ route('contact.index') }}">
                        <i class="flaticon-customer-support"></i>
                        <p>Kontak Layanan</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('product.index') ? 'active' : '' }}{{ Route::is('product.create') ? 'active' : '' }}{{ Route::is('product.edit') ? 'active' : '' }}{{ Route::is('facilities.index') ? 'active' : '' }}{{ Route::is('facilities.create') ? 'active' : '' }}{{ Route::is('houseType.index') ? 'active' : '' }}{{ Route::is('houseType.create') ? 'active' : '' }}">
                    <a href="{{ route('product.index') }}">
                        <i class="flaticon-home"></i>
                        <p>Produk Cluster</p>
                    </a>
                </li>
                
                
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Keamanan</h4>
                </li>
                <li class="nav-item {{ Route::is('cctv.index') ? 'active' : '' }}{{ Route::is('cctv.create') ? 'active' : '' }}{{ Route::is('cctv.edit') ? 'active' : '' }}">
                    <a href="{{ route('cctv.index') }}">
                        <i class="flaticon-photo-camera"></i>
                        <p>CCTV</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('security.index') ? 'active' : '' }}{{ Route::is('security.create') ? 'active' : '' }}{{ Route::is('security.edit') ? 'active' : '' }}{{ Route::is('securitySchedule.index') ? 'active' : '' }}{{ Route::is('securitySchedule.create') ? 'active' : '' }}">
                    <a href="{{ route('security.index') }}">
                        <i class="flaticon-layers-1"></i>
                        <p>Security</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('logs.admin') ? 'active' : '' }}{{ Route::is('logs.apps') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#audit">
                        <i class="flaticon-database"></i>
                        <p>Logs</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Route::is('logs.admin') ? 'show' : '' }}{{ Route::is('logs.apps') ? 'show' : '' }}" id="audit">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::is('logs.admin') ? 'active' : '' }}">
                                <a href="{{ route('logs.admin') }}">
                                    <span class="sub-item">Admin Panel</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('logs.apps') ? 'active' : '' }}">
                                <a href="{{ route('logs.apps') }}">
                                    <span class="sub-item">Aplikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Route::is('about.index') ? 'active' : '' }}{{ Route::is('about.create') ? 'active' : '' }}{{ Route::is('about.edit') ? 'active' : '' }}">
                    <a href="{{ route('about.index') }}">
                        <i class="flaticon-list"></i>
                        <p>Tentang Aplikasi</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('logs.panic') ? 'active' : '' }}">
                    <a href="{{ route('logs.panic') }}">
                        <i class="flaticon-alarm"></i>
                        <p>Log Panic Button</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->