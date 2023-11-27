<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FleetCart</title>

        <link rel="shortcut icon" href="{{ asset('build/assets/favicon.png') }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        @routes
        @vite([
            'resources/sass/install/main.scss',
            'resources/js/install/main.js'
        ])
    </head>

    <body class="ltr">
        <div id="app" class="installer-wrapper">
            <Install
                v-cloak
                class="installer-box d-flex flex-column flex-md-row"
                :requirement-satisfied="{{ $requirement->satisfied() ? 'true' : 'false' }}"
                :permission-provided="{{ $permission->provided() ? 'true' : 'false' }}"
                inline-template
            >
                <div>
                    <aside class="installer-left-sidebar d-flex flex-column justify-content-between">
                        <div class="logo d-flex justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 1 31 31" preserveAspectRatio="xMinYMid meet">
                                <g>
                                    <g transform="translate(-164 -55)">
                                        <g transform="translate(-457.529 -7971.529)">
                                            <rect width="29" height="29" rx="3" transform="translate(622.529 8028.529)" fill="#0068e1" id="logo_f_bg"></rect>
                                            <circle cx="2.062" cy="2.062" r="2.062" transform="translate(642.715 8048.341)" fill="#ffb135"></circle>
                                            <g transform="translate(622.529 8028.529)">
                                                <circle cx="23" cy="23" r="23" transform="translate(-25 -26)" fill="#ffffff" opacity="0.12"></circle>
                                                <circle cx="23" cy="23" r="23" transform="translate(15 23)" fill="#ffffff" opacity="0.12"></circle>
                                            </g>
                                            <g transform="translate(630.791 8033.531)">
                                                <g transform="translate(-200.084 -174)" stroke-miterlimit="10" fill="#0068e1" id="logo_f_text">
                                                    <path d="M 200.4339904785156 192.5772552490234 L 200.4339904785156 174.3499908447266 L 212.1227569580078 174.3499908447266 L 211.3375701904297 177.8291931152344 L 204.426025390625 177.8291931152344 L 204.0760192871094 177.8291931152344 L 204.0760192871094 178.1791839599609 L 204.0760192871094 181.8428344726563 L 204.0760192871094 182.1928405761719 L 204.426025390625 182.1928405761719 L 211.1689147949219 182.1928405761719 L 211.5615844726563 185.6720428466797 L 204.426025390625 185.6720428466797 L 204.0760192871094 185.6720428466797 L 204.0760192871094 186.0220336914063 L 204.0760192871094 191.9179992675781 L 200.4339904785156 192.5772552490234 Z" stroke="none" fill="#0068e1" id="logo_f_text_inner"></path>
                                                    <path d="M 200.7839965820313 174.6999969482422 L 200.7839965820313 192.158203125 L 203.7260284423828 191.6256561279297 L 203.7260284423828 186.0220336914063 L 203.7260284423828 185.3220367431641 L 204.426025390625 185.3220367431641 L 211.1698608398438 185.3220367431641 L 210.8561859130859 182.5428314208984 L 204.426025390625 182.5428314208984 L 203.7260284423828 182.5428314208984 L 203.7260284423828 181.8428344726563 L 203.7260284423828 178.1791839599609 L 203.7260284423828 177.4791870117188 L 204.426025390625 177.4791870117188 L 211.0577392578125 177.4791870117188 L 211.6849517822266 174.6999969482422 L 200.7839965820313 174.6999969482422 M 200.0839996337891 174 L 212.560546875 174 L 211.6173706054688 178.1791839599609 L 204.426025390625 178.1791839599609 L 204.426025390625 181.8428344726563 L 211.4816284179688 181.8428344726563 L 211.9533081054688 186.0220336914063 L 204.426025390625 186.0220336914063 L 204.426025390625 192.2103271484375 L 200.0839996337891 192.9962921142578 L 200.0839996337891 174 Z" stroke="none" fill="#ffffff"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>

                        <ul class="step-list list-inline">
                            <li
                                class="step-list-item d-flex position-relative"
                                :class="{
                                    'active': step === 1,
                                    'complete': step >= 2
                                }"
                            >
                                <div class="icon d-flex justify-content-center align-items-center rounded-circle">
                                    <span :class="step > 1 ? 'mdi mdi-check' : 'circle rounded-circle'"></span>
                                </div>

                                <div
                                    :class="{
                                        'cursor-pointer': !appInstalled && step !== 1 && !formSubmitting
                                    }"
                                    @click="goToStep(1)"
                                >
                                    <label class="title">Requirements</label>
                                    <span class="excerpt d-block">Check system requirements</span>
                                </div>
                            </li>

                            <li
                                class="step-list-item d-flex position-relative"
                                :class="{
                                    'active': step === 2,
                                    'complete': step >= 3
                                }"
                            >
                                <div class="icon d-flex justify-content-center align-items-center rounded-circle">
                                    <span :class="step > 2 ? 'mdi mdi-check' : 'circle rounded-circle'"></span>
                                </div>

                                <div
                                    :class="{
                                        'cursor-pointer': !appInstalled && step !== 2 && !formSubmitting
                                    }"
                                    @click="goToStep(2)"
                                >
                                    <label class="title">Permissions</label>
                                    <span class="excerpt d-block">Obtain necessary permissions</span>
                                </div>
                            </li>

                            <li
                                class="step-list-item d-flex position-relative"
                                :class="{
                                    'active': step === 3 && !appInstalled,
                                    'complete': appInstalled
                                }"
                            >
                                <div class="icon d-flex justify-content-center align-items-center rounded-circle">
                                    <span :class="appInstalled ? 'mdi mdi-check' : 'circle rounded-circle'"></span>
                                </div>

                                <div
                                    :class="{
                                        'cursor-pointer': !appInstalled && step !== 3 && !formSubmitting
                                    }"
                                    @click="goToStep(3)"
                                >
                                    <label class="title">Configuration</label>
                                    <span class="excerpt d-block">Configure the application</span>
                                </div>
                            </li>

                            <li
                                class="step-list-item d-flex position-relative"
                                :class="{
                                    'complete': appInstalled
                                }"
                            >
                                <div class="icon d-flex justify-content-center align-items-center rounded-circle">
                                    <span :class="appInstalled ? 'mdi mdi-check' : 'circle rounded-circle'"></span>
                                </div>

                                <div>
                                    <label class="title">Complete</label>
                                    <span class="excerpt d-block">Installation successful</span>
                                </div>
                            </li>
                        </ul>

                        <span class="app-version">Version {{ fleetcart_version() }}</span>
                    </aside>

                    <section class="installer-main-content flex-grow-1 overflow-hidden">
                        @yield('content')
                    </section>
                </div>
            </Install>
        </div>

        @stack('scripts')
    </body>
</html>
