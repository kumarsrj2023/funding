<div class="leftside-menu">
    <a href="javascript:void(0)" class="logo logo-light">
        <span class="logo-lg text-center">
            <img src="{{ asset('images/logo-white.svg?v=' . Helper::$images_asset_version) }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo-sm-white.svg?v=' . Helper::$images_asset_version) }}" alt="small logo">
        </span>
    </a>
    <a href="javascript:void(0)" class="logo logo-dark">
        <span class="logo-lg text-center">
            <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo.png?v=' . Helper::$images_asset_version) }}" alt="small logo">
        </span>
    </a>
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-title">Main</li>
            <li class="side-nav-item businesses">
                <a href="{{ route('home') }}" class="side-nav-link">
                    <i class="ri-shield-star-line"></i>
                    <span>Businesses</span>
                </a>
            </li>
            <li class="side-nav-item brokers">
                <a href="{{ route('broker.list') }}" class="side-nav-link">
                    <i class="ri-contacts-line"></i>
                    <span> Brokers </span>
                </a>
            </li>
            <li class="side-nav-item director-search">
                <a href="{{ route('director.search') }}" class="side-nav-link">
                    <i class="ri-user-search-line"></i>
                    <span> Director Search </span>
                </a>
            </li>
            <li class="side-nav-item transaction-form">
                <a href="{{ route('transaction.form') }}" class="side-nav-link">
                    <i class="ri-exchange-line"></i>
                    <span> Transaction Form </span>
                </a>
            </li>
            <li class="side-nav-item open-banking-transactions">
                <a href="{{ route('open.banking.transactions') }}" class="side-nav-link">
                    <i class="ri-exchange-box-line"></i>
                    <span> Open Banking Transactions </span>
                </a>
            </li>
            <li class="side-nav-item dd-control-form">
                <a href="{{ route('dd.control.add' ) }}" class="side-nav-link">
                    <i class="ri-shield-star-line"></i>
                    <span> DD Control </span>
                </a>
            </li>
            <li class="side-nav-item cais-file">
                <a href="{{ route('cais.file') }}" class="side-nav-link">
                    <i class="ri-file-pdf-line"></i>
                    <span> CAIS File </span>
                </a>
            </li>
            <li class="side-nav-item keywords">
                <a href="{{ route('keywords') }}" class="side-nav-link">
                    <i class="ri-file-word-line"></i>
                    <span> Keywords </span>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>