<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tournament') - growtopiacalculator.com</title>
    <meta name="description" content="@yield('meta_description', 'Tournament & kompetisi khusus Growtopia')">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* ── GrowtopiaHelp Brand Color Palette ── */
            --bg:       #061327;
            --surface:  #0a1b36;
            --card:     #0e2245;
            --card2:    #142d57;
            --border:   #1b3b6e;
            --border2:  #244b8a;

            /* ── Growtopia Royal Blue (Primary Brand #0e59c3) ── */
            --azure:        #4b84e0;
            --azure-dim:    #0e59c3;
            --azure-deep:   #0b479c;
            --azure-glow:   rgba(14,89,195,.2);
            --azure-xlight: rgba(14,89,195,.08);

            /* ── Championship Gold (Secondary) ── */
            --gold:       #F0B429;
            --gold-dim:   #C99120;
            --gold-glow:  rgba(240,180,41,.18);
            --gold-light: rgba(240,180,41,.1);

            /* ── Semantic ── */
            --green:       #34D399;
            --green-glow:  rgba(52,211,153,.12);
            --red:         #F87171;
            --red-glow:    rgba(248,113,113,.1);
            --orange:      #FB923C;

            /* ── Text ── */
            --text:        #EAF4FF;
            --text-mid:    #BFDBF7;
            --text-dim:    #6B8FB5;
            --text-muted:  #3B5F88;

            /* ── Radius ── */
            --r:    14px;
            --r-sm: 9px;
            --r-xs: 5px;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Subtle bg radial ─────────────────────────────── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 10% 0%,   rgba(56,189,248,.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 90% 100%, rgba(240,180,41,.05) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }

        /* ── Nav ─────────────────────────────────────────── */
        .nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(3,13,28,.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 36px;
            height: 62px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                var(--azure-dim) 25%,
                var(--gold) 75%,
                transparent 100%
            );
            opacity: .3;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .nav-brand-logo {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--azure-dim), var(--azure));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            box-shadow: 0 0 16px var(--azure-glow);
        }
        .nav-brand-text {
            font-size: 17px; font-weight: 800; letter-spacing: -0.4px;
            color: var(--text);
        }
        .nav-brand-text span { color: var(--gold); }
        .nav-right { display: flex; gap: 20px; align-items: center; }
        .nav-link {
            font-size: 13px; font-weight: 500;
            color: var(--text-dim); text-decoration: none;
            transition: color .18s;
        }
        .nav-link:hover { color: var(--azure); }

        /* ── Container ────────────────────────────────────── */
        .container { max-width: 1120px; margin: 0 auto; padding: 0 28px; position: relative; z-index: 1; }
        .section { padding: 52px 0; }

        /* ── Cards ────────────────────────────────────────── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            transition: border-color .22s, box-shadow .22s, transform .22s;
        }
        .card:hover {
            border-color: var(--border2);
            box-shadow: 0 8px 32px rgba(0,0,0,.4), 0 0 0 1px var(--azure-xlight);
            transform: translateY(-2px);
        }

        /* ── Badges ───────────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: var(--r-xs);
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px;
        }
        .badge-draft             { background: rgba(107,143,181,.12); color: var(--text-dim); border: 1px solid rgba(107,143,181,.2); }
        .badge-active            { background: var(--green-glow); color: var(--green); border: 1px solid rgba(52,211,153,.2); }
        .badge-hidden            { background: rgba(107,143,181,.12); color: var(--text-dim); border: 1px solid rgba(107,143,181,.2); }
        .badge-revealed          { background: var(--azure-xlight); color: var(--azure); border: 1px solid rgba(56,189,248,.2); }
        .badge-registration_open { background: var(--green-glow); color: var(--green); border: 1px solid rgba(52,211,153,.2); }
        .badge-reg-closed        { background: rgba(251,146,60,.1); color: var(--orange); border: 1px solid rgba(251,146,60,.2); }
        .badge-ongoing           { background: var(--azure-glow); color: var(--azure); border: 1px solid rgba(56,189,248,.25); }
        .badge-finished          { background: rgba(107,143,181,.1); color: var(--text-dim); border: 1px solid rgba(107,143,181,.15); }

        /* ── Buttons ──────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 11px 22px; border-radius: var(--r-sm);
            border: none; font-size: 14px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: all .2s; font-family: inherit; white-space: nowrap;
        }
        .btn-primary {
            background: var(--azure-dim);
            color: #fff;
            box-shadow: 0 2px 12px rgba(14,165,233,.3);
        }
        .btn-primary:hover {
            background: var(--azure);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(14,165,233,.4);
        }
        .btn-gold {
            background: var(--gold);
            color: var(--bg); font-weight: 800;
            box-shadow: 0 2px 12px var(--gold-glow);
        }
        .btn-gold:hover {
            background: #F5C043;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px var(--gold-glow);
        }
        .btn-register {
            background: var(--azure-dim); color: #fff; font-weight: 700;
            box-shadow: 0 2px 12px rgba(14,165,233,.25);
        }
        .btn-register:hover {
            background: var(--azure); transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(14,165,233,.4);
        }
        .btn-register:disabled { opacity: .35; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-ghost {
            background: rgba(56,189,248,.08);
            border: 1px solid var(--border2);
            color: var(--text-dim);
        }
        .btn-ghost:hover {
            background: var(--azure-xlight);
            border-color: rgba(56,189,248,.3);
            color: var(--azure);
        }

        /* ── Utilities ───────────────────────────────────── */
        .flex { display: flex; } .items-center { align-items: center; } .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 12px; }
        .muted { color: var(--text-dim); }
        .text-sm { font-size: 14px; }
        .page-title { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }

        /* ── Modals ───────────────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(3,13,28,.8);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal {
            background: var(--card2);
            border: 1px solid var(--border2);
            border-radius: 20px;
            padding: 40px; width: 460px; max-width: 92vw;
            transform: translateY(18px) scale(.97);
            transition: transform .3s cubic-bezier(.34,1.56,.64,1);
            box-shadow: 0 40px 80px rgba(0,0,0,.6), 0 0 0 1px var(--azure-xlight);
        }
        .modal-overlay.open .modal { transform: none; }
        .modal-title { font-size: 21px; font-weight: 800; margin-bottom: 6px; }
        .modal-sub { color: var(--text-dim); font-size: 14px; margin-bottom: 28px; }
        .form-label {
            display: block; font-size: 11px; font-weight: 700;
            color: var(--text-dim); margin-bottom: 7px;
            text-transform: uppercase; letter-spacing: .7px;
        }
        .form-control {
            width: 100%; background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: var(--r-sm);
            padding: 12px 15px; color: var(--text);
            font-size: 14px; font-family: inherit;
            outline: none; transition: border-color .18s, box-shadow .18s;
        }
        .form-control:focus {
            border-color: var(--azure);
            box-shadow: 0 0 0 3px var(--azure-xlight);
        }
        .form-error { color: var(--red); font-size: 13px; margin-top: 6px; }
        .modal-actions { display: flex; gap: 10px; margin-top: 22px; }
        .btn-cancel {
            background: transparent; border: 1px solid var(--border2);
            color: var(--text-dim); padding: 11px 22px;
            border-radius: var(--r-sm); cursor: pointer;
            font-size: 14px; font-family: inherit; transition: all .18s;
        }
        .btn-cancel:hover { background: var(--azure-xlight); border-color: rgba(56,189,248,.3); }
    </style>
    @stack('styles')
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="nav-brand">
        <div class="nav-brand-logo"><img src="{{ asset('images/items/exp_potion.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></div>
        <span class="nav-brand-text">Tournament <span>GT</span></span>
    </a>
    <div class="nav-right">
        <a href="https://growtopiacalculator.com" class="nav-link" target="_blank">growtopiacalculator.com ↗</a>
    </div>
</nav>

@yield('content')

@stack('scripts')
</body>
</html>


