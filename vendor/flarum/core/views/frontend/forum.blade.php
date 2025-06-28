{!! $forum['headerHtml'] !!}

<div id="app" class="App">

    <div id="app-navigation" class="App-navigation"></div>

    <div id="drawer" class="App-drawer">

        <header id="header" class="App-header">
            <div id="header-navigation" class="Header-navigation" style="margin-top: 10px;"></div>
            <div class="container" style="margin-top: 10px;">
                <div class="Header-title">
                    <a href="{{ $forum['baseUrl'] }}" id="home-link">
                        @if ($forum['logoUrl'])
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <img src="{{ $forum['logoUrl'] }}" alt="{{ $forum['title'] }}" class="Header-logo"
                                    style="max-height: 40px">
                                <div>
                                    <h1 style="font-size: 14.2px; font-weight: 300; margin: 0" class="text-[#319527]">
                                        Diễn đàn học sinh</h1>
                                    <h1 style="font-weight: 700; margin: 0; font-size: 14.2px;" class="text-[#319527]">
                                        Chuyên Biên Hòa</h1>
                                </div>
                            </div>
                        @else
                            {{ $forum['title'] }}
                        @endif
                    </a>
                </div>
                <div id="header-primary" class="Header-primary"></div>
                <div id="header-secondary" class="Header-secondary"></div>
            </div>
        </header>

    </div>

    <main class="App-content">
        <div id="content"></div>

        {!! $content !!}

        <div class="App-composer">
            <div class="container">
                <div id="composer"></div>
            </div>
        </div>
    </main>

</div>

{!! $forum['footerHtml'] !!}
