<div class="tab-pane fade show active" id="search" role="tabpanel" aria-labelledby="search-tab">

    <input type="hidden" id="denemeke123" value="zaa">

    <div class="container mb-4 px-0 show pt-0" id="filtercollapse">
        {{-- <button id="allGroupsButton" onclick='btnGroupAll(this)' class="btn mb-2 btn-default">Tümü</button>
        @foreach ($ugrup as $u)
            <button onclick='btnGrupClick(this)' value='{{ $u->UrunGrubu_id }}'
                class="btn mb-2 btn-outline-default omaclasse"> {{ __($u->Urungrubu) }}</button>
        @endforeach --}}

        {{-- <button id="allGroupsButton" onclick='btnGroupAll(this)' class="category">
            <div class="overlay"></div>
            <div class="content">
                <div class="category-text">Tümü</div>
                <div class="category-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-8 -5 24 24" width="24" fill="currentColor">
                        <path
                            d="M5.314 7.071l-4.95-4.95A1 1 0 0 1 1.778.707l5.657 5.657a1 1 0 0 1 0 1.414l-5.657 5.657a1 1 0 0 1-1.414-1.414l4.95-4.95z">
                        </path>
                    </svg>
                </div>
            </div>
        </button> --}}

        <button id="backButton" onclick='btnGroupBack(this)' class="category-back n-hidden">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-8 -5 24 24" width="24" fill="currentColor">
                    <path
                        d="M2.757 7l4.95 4.95a1 1 0 1 1-1.414 1.414L.636 7.707a1 1 0 0 1 0-1.414L6.293.636A1 1 0 0 1 7.707 2.05L2.757 7z">
                    </path>
                </svg>
                Geri
            </div>
            <span id="backButtonSpan"></span>
        </button>

        <a href="/menu" style="display: flex; align-items: center; justify-content: space-between; background: #1e293b; color: white; padding: 1.5rem; border-radius: 16px; text-decoration: none; margin-bottom: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="font-size: 1.3rem; font-weight: 600; letter-spacing: 0.5px;">Menüyü Görüntüle</div>
            <div style="background: rgba(255,255,255,0.1); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-8 -5 24 24" width="20" fill="currentColor">
                    <path d="M5.314 7.071l-4.95-4.95A1 1 0 0 1 1.778.707l5.657 5.657a1 1 0 0 1 0 1.414l-5.657 5.657a1 1 0 0 1-1.414-1.414l4.95-4.95z"></path>
                </svg>
            </div>
        </a>

        <div class="row" id="subGroup">
        </div>
    </div>
    <div class="container mb-4 MT-">
        <div class="row" id="groupFilterRow">
        </div>
    </div>

</div>
