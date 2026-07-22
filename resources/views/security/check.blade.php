@extends('layouts.master')

@section('title', 'Security Auditor Dashboard')

@section('content')
<div class="row mb-5">
    <div class="col-lg-12">
        <h1 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-indigo me-2"></i>Security Auditor Dashboard</h1>
        <p class="text-muted mb-0">Audit SSL certificates, evaluate credentials robustness, and analyze web vulnerability mitigations.</p>
    </div>
</div>

<div class="row g-4">
    <!-- PKI Certificate Authority Info -->
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fa-solid fa-file-signature text-success me-2"></i>SSL Certificate Authority</h4>
                <span class="badge bg-success">Education Root CA</span>
            </div>
            <div class="card-body p-4">
                @if($caInfo)
                    <div class="mb-3">
                        <span class="text-muted d-block small">Issuer:</span>
                        <strong class="text-light">{{ $caInfo['issuer'] }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Subject:</span>
                        <strong class="text-light">{{ $caInfo['subject'] }}</strong>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <span class="text-muted d-block small">Valid From:</span>
                            <span class="text-light small">{{ $caInfo['validFrom'] }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <span class="text-muted d-block small">Valid To:</span>
                            <span class="text-light small">{{ $caInfo['validTo'] }}</span>
                        </div>
                    </div>
                    <div class="alert alert-success border-0 py-2 text-center mb-0 small" style="background-color: rgba(16, 185, 129, 0.15); color: #a7f3d0; border-radius: 8px;">
                        <i class="fa-solid fa-shield-check me-1"></i> Root CA Certificate is Active & Secure
                    </div>
                @else
                    <div class="alert alert-warning border-0 text-center py-4" style="background-color: rgba(245, 158, 11, 0.15); color: #fef08a; border-radius: 12px;">
                        <i class="fa-solid fa-triangle-exclamation fs-2 mb-2 d-block"></i>
                        Root CA Certificate Not Generated Yet.<br>
                        <span class="small text-muted">Please trigger certificate generation on the server first.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Domain Certificate Info -->
    <div class="col-md-6">
        <div class="card card-custom h-100">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fa-solid fa-globe text-info me-2"></i>Website Certificate</h4>
                <span class="badge bg-info">www.secure-study.com</span>
            </div>
            <div class="card-body p-4">
                @if($siteInfo)
                    <div class="mb-3">
                        <span class="text-muted d-block small">Common Name (CN):</span>
                        <strong class="text-light">{{ $siteInfo['subject'] }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Authority Signer:</span>
                        <strong class="text-light">{{ $siteInfo['issuer'] }}</strong>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <span class="text-muted d-block small">Valid From:</span>
                            <span class="text-light small">{{ $siteInfo['validFrom'] }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <span class="text-muted d-block small">Valid To (1 Year Expiry):</span>
                            <span class="text-light small">{{ $siteInfo['validTo'] }}</span>
                        </div>
                    </div>
                    @if($siteInfo['expired'])
                        <div class="alert alert-danger border-0 py-2 text-center mb-0 small" style="background-color: rgba(239, 68, 68, 0.15); color: #fecaca; border-radius: 8px;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> Website Certificate has Expired!
                        </div>
                    @else
                        <div class="alert alert-info border-0 py-2 text-center mb-0 small" style="background-color: rgba(59, 130, 246, 0.15); color: #bfdbfe; border-radius: 8px;">
                            <i class="fa-solid fa-lock me-1"></i> Website SSL Certificate matches domain and is Active
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning border-0 text-center py-4" style="background-color: rgba(245, 158, 11, 0.15); color: #fef08a; border-radius: 12px;">
                        <i class="fa-solid fa-triangle-exclamation fs-2 mb-2 d-block"></i>
                        Website SSL Certificate Not Generated Yet.<br>
                        <span class="small text-muted">Please trigger certificate generation on the server first.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Interactive Vulnerability Simulator -->
<h3 class="fw-bold text-light mt-5 mb-4"><i class="fa-solid fa-bug-slash text-indigo me-2"></i>Vulnerability & Mitigation Simulator</h3>

<div class="card card-custom mb-5">
    <div class="card-header card-header-custom p-0 border-bottom-0">
        <ul class="nav nav-tabs nav-fill bg-dark" id="vulnerabilityTab" role="tablist" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-light py-3 border-0 rounded-0" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-panel" type="button" role="tab" aria-selected="true">
                    <i class="fa-solid fa-key text-warning me-2"></i> Weak Passwords
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-light py-3 border-0 rounded-0" id="sqli-tab" data-bs-toggle="tab" data-bs-target="#sqli-panel" type="button" role="tab" aria-selected="false">
                    <i class="fa-solid fa-database text-danger me-2"></i> SQL Injection (SQLi)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-light py-3 border-0 rounded-0" id="xss-tab" data-bs-toggle="tab" data-bs-target="#xss-panel" type="button" role="tab" aria-selected="false">
                    <i class="fa-solid fa-code text-info me-2"></i> Cross-Site Scripting (XSS)
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="vulnerabilityTabContent">
            
            <!-- Password Analyzer Panel -->
            <div class="tab-pane fade show active" id="password-panel" role="tabpanel" aria-labelledby="password-tab">
                <h5>Password Robustness Evaluator</h5>
                <p class="text-muted small">Analyze passwords against the course validation standard: <code>Password::min(8)->letters()->mixedCase()->numbers()->symbols()</code></p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_input" class="form-label">Test Password</label>
                            <input type="text" class="form-control text-light bg-dark border-secondary" id="password_input" placeholder="Type a password to test...">
                        </div>
                        <div class="mt-4 p-3 bg-dark-panel" style="border-radius: 12px;">
                            <h6><i class="fa-solid fa-circle-info me-1 text-indigo"></i> Required Policy:</h6>
                            <ul class="mb-0 small text-muted">
                                <li>Enforce minimum length of 8 characters.</li>
                                <li>Enforce presence of lowercase and uppercase letters.</li>
                                <li>Enforce presence of numbers.</li>
                                <li>Enforce presence of special characters/symbols.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 border-start border-secondary">
                        <div class="p-3 bg-dark-panel h-100" style="border-radius: 12px;">
                            <h6>Audit Evaluation:</h6>
                            <div class="d-flex flex-column gap-3 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-light">Min Length (8+):</span>
                                    <span id="p_length" class="badge badge-student">Awaiting Input</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-light">Contains Letters:</span>
                                    <span id="p_letters" class="badge badge-student">Awaiting Input</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-light">Casing (Upper + Lower):</span>
                                    <span id="p_casing" class="badge badge-student">Awaiting Input</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-light">Contains Numbers:</span>
                                    <span id="p_numbers" class="badge badge-student">Awaiting Input</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-light">Contains Symbols:</span>
                                    <span id="p_symbols" class="badge badge-student">Awaiting Input</span>
                                </div>
                                <hr class="border-secondary my-1">
                                <div class="text-center py-2 rounded" id="password_status" style="background-color: rgba(255,255,255,0.05); font-weight: 600;">
                                    Type a password to start auditing.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- SQL Injection Panel -->
            <div class="tab-pane fade" id="sqli-panel" role="tabpanel" aria-labelledby="sqli-tab">
                <h5>SQL Injection Demonstration & Parameterized Defense</h5>
                <p class="text-muted small">Compare raw query string concatenation (highly vulnerable) against Laravel Eloquent/PDO parameter binding (highly secure).</p>
                
                <div class="row mt-4">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="sqli_input" class="form-label">User Input Value (e.g. Email)</label>
                            <input type="text" class="form-control text-light bg-dark border-secondary" id="sqli_input" value="student@study.com">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary-custom btn-sm" onclick="setSqliPayload('student@study.com')">Clean Input</button>
                            <button class="btn btn-danger btn-sm text-light border-0" onclick="setSqliPayload('\' OR \'1\'=\'1')">SQLi Payload</button>
                            <button class="btn btn-danger btn-sm text-light border-0" onclick="setSqliPayload('admin@study.com\' --')">Admin Bypass</button>
                        </div>
                    </div>
                    <div class="col-md-7 border-start border-secondary">
                        <div class="p-3 bg-dark-panel mb-3" style="border-radius: 12px;">
                            <h6 class="text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i> Vulnerable Raw Query (Concatenation):</h6>
                            <code class="text-wrap d-block p-2 bg-black text-danger font-monospace mt-2 small" id="sqli_vuln_query" style="border-radius: 6px;">
                                SELECT * FROM users WHERE email = 'student@study.com' AND password = 'hashed_password'
                            </code>
                        </div>
                        
                        <div class="p-3 bg-dark-panel mb-3" style="border-radius: 12px;">
                            <h6 class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Secure Query (Parameterized Binding):</h6>
                            <code class="text-wrap d-block p-2 bg-black text-success font-monospace mt-2 small" id="sqli_secure_query" style="border-radius: 6px;">
                                SELECT * FROM users WHERE email = ? AND password = ?
                            </code>
                            <div class="small text-muted mt-2">
                                Bound Parameter: <strong class="text-info" id="sqli_bindings">['student@study.com']</strong>
                            </div>
                        </div>
 
                        <div class="p-3 border" id="sqli_explanation_box" style="border-radius: 12px; background-color: rgba(59, 130, 246, 0.15); border-color: #3b82f6; color: #bfdbfe;">
                            Type inputs to view code parameters.
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- XSS Panel -->
            <div class="tab-pane fade" id="xss-panel" role="tabpanel" aria-labelledby="xss-tab">
                <h5>Cross-Site Scripting (XSS) & Blade Escaping Defense</h5>
                <p class="text-muted small">See how Laravel's native double curly braces <code>@{{ $value }}</code> render user inputs safely compared to unsafe raw outputs <code>@{!! $value !!}</code>.</p>
                
                <div class="row mt-4">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="xss_input" class="form-label">User Raw Input (Simulated comment/bio)</label>
                            <textarea class="form-control text-light bg-dark border-secondary" id="xss_input" rows="3">Hello World!</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-secondary-custom btn-sm" onclick="setXssPayload('Hello World!')">Clean Text</button>
                            <button class="btn btn-danger btn-sm text-light border-0" onclick="setXssPayload('<script>alert(&quot;Hack!&quot;)</script>')">Script Alert</button>
                            <button class="btn btn-danger btn-sm text-light border-0" onclick="setXssPayload('<img src=x onerror=alert(1)>')">Image Error</button>
                        </div>
                    </div>
                    <div class="col-md-7 border-start border-secondary">
                        <div class="p-3 bg-dark-panel mb-3" style="border-radius: 12px;">
                            <h6 class="text-success"><i class="fa-solid fa-shield-halved me-1"></i> Laravel Escaped output: <code>@{{ $input }}</code></h6>
                            <div class="p-2 bg-black border border-secondary text-info small mt-2 font-monospace" style="border-radius: 6px;" id="xss_escaped_code">
                                Hello World!
                            </div>
                            <div class="small text-muted mt-2">
                                Renders literally as text in browser. Secure!
                            </div>
                        </div>
 
                        <div class="p-3 bg-dark-panel mb-3" style="border-radius: 12px;">
                            <h6 class="text-danger"><i class="fa-solid fa-skull-crossbones me-1"></i> Unescaped output: <code>@{!! $input !!}</code></h6>
                            <div class="p-2 bg-black border border-secondary text-danger small mt-2 font-monospace" style="border-radius: 6px;" id="xss_raw_code">
                                Hello World!
                            </div>
                            <div class="small text-muted mt-2">
                                Executes direct HTML inside the page. Vulnerable!
                            </div>
                        </div>
 
                        <div class="p-3 border" id="xss_explanation_box" style="border-radius: 12px; background-color: rgba(245, 158, 11, 0.15); border-color: #f59e0b; color: #fef08a;">
                            Type inputs to check HTML escape results.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // JS for Ajax testbeds
    document.addEventListener('DOMContentLoaded', function() {
        
        // Setup Password testing
        const pwdInput = document.getElementById('password_input');
        pwdInput.addEventListener('input', function() {
            testPassword(this.value);
        });
        
        // Setup SQLi testing
        const sqliInput = document.getElementById('sqli_input');
        sqliInput.addEventListener('input', function() {
            testSql(this.value);
        });
        
        // Setup XSS testing
        const xssInput = document.getElementById('xss_input');
        xssInput.addEventListener('input', function() {
            testXss(this.value);
        });

        // Trigger initial checks
        testPassword('');
        testSql(sqliInput.value);
        testXss(xssInput.value);
    });

    function setSqliPayload(val) {
        document.getElementById('sqli_input').value = val;
        testSql(val);
    }

    function setXssPayload(val) {
        document.getElementById('xss_input').value = val;
        testXss(val);
    }

    function testPassword(val) {
        fetch("{{ route('security_test_password') }}?password=" + encodeURIComponent(val))
            .then(res => res.json())
            .then(data => {
                updateBadge('p_length', data.rules.length);
                updateBadge('p_letters', data.rules.letters);
                updateBadge('p_casing', data.rules.mixedCase);
                updateBadge('p_numbers', data.rules.numbers);
                updateBadge('p_symbols', data.rules.symbols);

                const statusBox = document.getElementById('password_status');
                if (data.passed) {
                    statusBox.className = 'text-center py-2 rounded text-success';
                    statusBox.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
                    statusBox.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Pass - Password meets robust policies!';
                } else {
                    statusBox.className = 'text-center py-2 rounded text-danger';
                    statusBox.style.backgroundColor = 'rgba(239, 68, 68, 0.15)';
                    statusBox.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Fail - Password violates complexity policy.';
                }
            });
    }

    function updateBadge(id, passed) {
        const el = document.getElementById(id);
        if (passed) {
            el.className = 'badge badge-instructor';
            el.innerText = 'Pass';
        } else {
            el.className = 'badge badge-admin';
            el.innerText = 'Fail';
        }
    }

    function testSql(val) {
        fetch("{{ route('security_test_sqli') }}?payload=" + encodeURIComponent(val))
            .then(res => res.json())
            .then(data => {
                document.getElementById('sqli_vuln_query').innerText = data.vulnerableQuery;
                document.getElementById('sqli_secure_query').innerText = data.secureQuery;
                document.getElementById('sqli_bindings').innerText = JSON.stringify(data.bindings);
                
                const expBox = document.getElementById('sqli_explanation_box');
                expBox.innerText = data.explanation;
                if (data.hasSqlPattern) {
                    expBox.style.borderColor = '#ef4444';
                    expBox.style.color = '#fecaca';
                    expBox.style.backgroundColor = 'rgba(239, 68, 68, 0.15)';
                } else {
                    expBox.style.borderColor = '#10b981';
                    expBox.style.color = '#a7f3d0';
                    expBox.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
                }
            });
    }

    function testXss(val) {
        fetch("{{ route('security_test_xss') }}?payload=" + encodeURIComponent(val))
            .then(res => res.json())
            .then(data => {
                document.getElementById('xss_escaped_code').innerText = data.escapedOutput;
                document.getElementById('xss_raw_code').innerText = data.rawOutput;
                
                const expBox = document.getElementById('xss_explanation_box');
                expBox.innerText = data.explanation;
                if (data.hasHtmlTags) {
                    expBox.style.borderColor = '#f59e0b';
                    expBox.style.color = '#fef08a';
                    expBox.style.backgroundColor = 'rgba(245, 158, 11, 0.15)';
                } else {
                    expBox.style.borderColor = '#10b981';
                    expBox.style.color = '#a7f3d0';
                    expBox.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
                }
            });
    }
</script>
@endsection
