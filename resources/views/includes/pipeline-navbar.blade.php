<div class="row">
    <ul class="nav nav-tabs mb-2">        
        <li class="nav-item">
            <a href="{{ route('bca.pipeline') }}"
                aria-expanded="{{ request()->route()->getName() == 'bca.pipeline' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'bca.pipeline' ? 'active' : '' }}">
                BCA
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sif.pipeline') }}"
                aria-expanded="{{ request()->route()->getName() == 'sif.pipeline' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'sif.pipeline' ? 'active' : '' }}">
                SIF
            </a>
        </li>
    </ul>
</div>