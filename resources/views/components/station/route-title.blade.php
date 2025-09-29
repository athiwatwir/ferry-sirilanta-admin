@props([
'departStation'=>[],
'destStation'=>[]
])
<div class="row">
    <div class="col-12 col-lg-5 text-center">
        <h4 class="mb-0 text-primary">[{{ $departStation['nickname'] }}] {{ $departStation['name'] }} </h4>
        @if (!empty($departStation['piername']))
        <strong>({{ $departStation['piername'] }})</strong>
        @endif
    </div>

    <div class="col-12 col-lg-2">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
            <defs>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#00f260">
                        <animate attributeName="stop-color" values="#00f260;#0575e6;#00f260" dur="4s" repeatCount="indefinite" />
                    </stop>
                    <stop offset="100%" stop-color="#0575e6">
                        <animate attributeName="stop-color" values="#0575e6;#00f260;#0575e6" dur="4s" repeatCount="indefinite" />
                    </stop>
                </linearGradient>
            </defs>
            <!-- เติม fill="url(#grad1)" -->
            <path fill="url(#grad1)" d="M12.089 3.634a2 2 0 0 0 -1.089 1.78l-.001 2.585l-1.999 .001a1 1 0 0 0 -1 1v6l.007 .117a1 1 0 0 0 .993 .883l1.999 -.001l.001 2.587a2 2 0 0 0 3.414 1.414l6.586 -6.586a2 2 0 0 0 0 -2.828l-6.586 -6.586a2 2 0 0 0 -2.18 -.434l-.145 .068z" />
            <path fill="url(#grad1)" d="M3 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
            <path fill="url(#grad1)" d="M6 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
        </svg>

    </div>

    <div class="col-12 col-lg-5">
        <h4 class="mb-0 text-primary">[{{ $destStation['nickname'] }}] {{ $destStation['name'] }} </h4>
        @if (!empty($destStation['piername']))
        <strong>({{ $destStation['piername'] }})</strong>
        @endif
    </div>
</div>
