<div class="row">
    <ul class="nav nav-tabs mb-2">
        <li class="nav-item">
            <a href="{{ route('businesses.customer.info', $id) }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.customer.info' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.customer.info' ? 'active' : '' }}">
                Customer Info
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.business.info', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.business.info' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.business.info' ? 'active' : '' }}">
                Business Info
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.loan.info', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.loan.info' || request()->route()->getName() == 'businesses.loan.info.add' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.loan.info'  || request()->route()->getName() == 'businesses.loan.info.add' ? 'active' : '' }}">
                Loan Info
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.director.info', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.director.info' || request()->route()->getName() == 'businesses.director.info.add' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.director.info' || request()->route()->getName() == 'businesses.director.info.add' ? 'active' : '' }}">
                Director Info
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.open.banking.accounts', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.open.banking.accounts' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.open.banking.accounts' ? 'active' : '' }}">
                Open Banking Accounts
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.committee.meeting', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.committee.meeting' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.committee.meeting' ? 'active' : '' }}">
                Committee Meeting
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.card.payment', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.card.payment' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.card.payment' ? 'active' : '' }}">
                Card Payments
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.open.banking.payments', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.open.banking.payments' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.open.banking.payments' ? 'active' : '' }}">
                Open Banking Payments
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('sop.price.model', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'sop.price.model' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'sop.price.model' ? 'active' : '' }}">
                Pricing Model
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ !empty($id) ? route('businesses.committeepaper', $id) : 'javascript:void(0)' }}"
                aria-expanded="{{ request()->route()->getName() == 'businesses.committeepaper' ? 'true' : 'false' }}"
                class="nav-link {{ request()->route()->getName() == 'businesses.committeepaper' ? 'active' : '' }}">
                Committee Paper
            </a>
        </li>
        <li class="nav-item">
            <a type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#sopModel" id="openModalBtn">
                Send SOP
            </a>
        </li>
    </ul>
</div>

@include('sop.modals')
