@extends('layouts.app')
@section('title', "About Us & Privacy Policy")

@section('content')
<div class="max-w-4xl mx-auto space-y-10 py-6">

    <!-- Header Section -->
    <div class="text-center space-y-4">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
            About Us & Privacy Policy
        </h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
            Everything you need to know about who we are, what we do, and how we handle your data.
        </p>
    </div>

    <!-- About Us Card -->
    <div class="glass-card rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-brand-400/10 to-blue-400/10 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition duration-700"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-brand-50 rounded-2xl flex items-center justify-center border border-brand-100/50 shadow-inner">
                    <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">About Growtopia Calculator</h2>
            </div>
            
            <div class="space-y-4 text-slate-600 leading-relaxed font-medium">
                <p>
                    Growtopia Calculator is a dedicated web application designed to help players of the popular sandbox MMO game <strong>Growtopia</strong> calculate their profits and make informed trading decisions.
                </p>
                <p>
                    Our calculators are built to provide accurate estimations for breaking packs, crafting items, and farming. By inputting your raw materials, costs, and selling prices, you can effortlessly calculate your margins and maximize your in-game profit.
                </p>
                <p>
                    We are a community-driven project and are completely independent. Growtopia Calculator is not affiliated with, endorsed, sponsored, or specifically approved by Ubisoft or the official Growtopia team. All game assets, images, and trademarks belong to their respective owners.
                </p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Card -->
    <div class="glass-card rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="absolute -left-20 -top-20 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition duration-700"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-100/50 shadow-inner">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Privacy Policy</h2>
            </div>
            
            <div class="space-y-6 text-slate-600 leading-relaxed font-medium">
                
                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">1. Information We Collect</h3>
                    <p>
                        Growtopia Calculator is designed to be privacy-first. We do not require you to create an account, and we do not collect personal identifying information such as your name, email address, or in-game credentials. Any calculation data or preference settings are stored locally on your device using standard browser storage (like LocalStorage or Cookies).
                    </p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">2. Analytics & Advertising</h3>
                    <p>
                        To keep this tool free for the community, we may use third-party services such as Google Analytics to understand how our website is used and Google AdSense to display relevant advertisements. 
                    </p>
                    <ul class="list-disc list-inside mt-2 space-y-1 ml-2 text-slate-500">
                        <li>These third-party vendors, including Google, use cookies to serve ads based on your prior visits to our website or other websites.</li>
                        <li>Google's use of advertising cookies enables it and its partners to serve ads based on your browsing history.</li>
                        <li>You may opt out of personalized advertising by visiting <a href="https://myadcenter.google.com/" target="_blank" rel="noopener noreferrer" class="text-brand-600 hover:underline">Google's Ads Settings</a>. Alternatively, you can opt out of a third-party vendor's use of cookies for personalized advertising by visiting <a href="https://www.aboutads.info" target="_blank" rel="noopener noreferrer" class="text-brand-600 hover:underline">www.aboutads.info</a>.</li>
                        <li><strong>Advertiser Transparency:</strong> We maintain an up-to-date <code>ads.txt</code> file on our domain to verify our authorized digital sellers, ensuring a secure advertising ecosystem.</li>
                    </ul>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">3. Feedback and User Submissions</h3>
                    <p>
                        If you choose to use our Feedback system to report bugs or suggest features, we collect the information you voluntarily provide. This includes the name you provide, the content of your message, and any optional screenshots you upload. 
                        This data is securely stored in our database solely for the purpose of improving Growtopia Calculator and is never shared with third parties. We ask that you do not include sensitive personal information in your feedback submissions.
                    </p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">4. External Links and Community Promotions</h3>
                    <p>
                        Our website features community banners and promotions that link to external websites (such as Discord servers or other platforms). We do not control these third-party sites and are not responsible for their content or privacy practices. Once you click a promotional banner and leave our site, you are subject to the privacy policy of that specific destination.
                    </p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">5. Data Security</h3>
                    <p>
                        All calculator computations happen entirely within your browser—none of your calculator data is transmitted to our servers. Data that is transmitted to our servers (such as feedback submissions) is stored securely. While no system is 100% secure, we implement standard security measures to protect your submissions.
                    </p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">6. Contact Us</h3>
                    <p>
                        If you have any questions or concerns regarding this privacy policy or the website in general, please contact us via email at <strong>admin@yourdomain.com</strong> or through our community channels.
                    </p>
                </section>
                
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-sm text-slate-400">
                        Last updated: May 28, 2026
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
