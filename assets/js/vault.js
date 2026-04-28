/**
 * Iron & Oxygen - Benchmark Vault & % Calculator
 * vault.js - Pure client-side, no page reloads.
 * 
 * Features: 
 *      - 1RM percentage table (50%-95% in 5% steps)
 *      - Unit toggle (lb / kg)
 *      - Rounding selector (5 / 2.5 / 1 / none)
 *      - Copy table to clipboard
 *      - Tab switching
 *      - Benchmark accordion
 *      - Live search + category filter
 */
(function () {
    'use strict';

    // CONSTSNTS
    const PERCENTAGES = [50, 55, 60, 65, 70, 75, 80, 85, 90, 95];

    const ZONES = {
        50: {label:'Warm-Up', cls:'warm'},
        55: {label:'Warm-Up', cls:'warm'},
        60: {label:'Build', cls:'build'},
        65: {label:'Build', cls:'build'},
        70: {label:'Work', cls:'work'},
        75: {label:'Work', cls:'work'},
        80: {label:'Work', cls:'work'},
        85: {label:'Heavy', cls:'heavy'},
        90: {label:'Heavy', cls:'heavy'},
        95: {label:'Max Effort', cls:'max'},
    };

    // STATE
    let state = {
        unit:   'lb',
        round:  5, 
        oneRM:  '',
        lift:   'Back Squat',
    };

    // DOM REFs (grabbed once after DOMContentLoaded)
    let $root, $tabBtns, $panels, $liftSelect, $liftCustom, $oneRM, $unitBtns, $roundBtns, $resultWrap, $resultTitle, 
        $tableBody, $tableWeightHeader, $copyBtn, $search, $catFilter, $benchmarkList, $benchmarkCards, $benchmarkCount,
        $emptyMsg;
        
    // INIT
    function init() {
        $root = document.getElementById('ioVaultRoot');
        if (!$root) return;

        // Calculator refs
        $liftSelect         = $root.querySelector('#ioLiftName');
        $liftCustom         = $root.querySelector('#ioLiftCustom');
        $oneRM              = $root.querySelector('#io1RM');
        $unitBtns           = $root.querySelectorAll('.io-unit-btn');
        $roundBtns          = $root.querySelectorAll('.io-round-btn');
        $resultWrap         = $root.querySelector('#ioResultWrap');
        $resultTitle        = $root.querySelector('#ioResultTitle');
        $tableBody          = $root.querySelector('#ioPctTableBody');
        $tableWeightHeader  = $root.querySelector('#ioTableWeightHeader');
        $copyBtn            = $root.querySelector('#ioCopyBtn');

        // Vault refs
        $search             = $root.querySelector('#ioSearch');
        $catFilter          = $root.querySelector('#ioCategoryFilter');
        $benchmarkList      = $root.querySelector('#ioBenchmarkList');
        $benchmarkCards     = $root.querySelectorAll('.io-benchmark-card');
        $benchmarkCount     = $root.querySelector('#ioBenchmarkCount');
        $emptyMsg           = $root.querySelector('#ioBenchmarkEmpty');

        // Tab refs
        $tabBtns            = $root.querySelectorAll('.io-tab-btn');
        $panels             = $root.querySelectorAll('.io-panel');

        bindTabs();
        bindCalculator();
        bindVault();

        // Activate first panel when tabs are shown
        if ( $tabBtns.length ) {
            const firstPanel = $root.querySelector('#tab-panel-calc') || 
                               $root.querySelector('.io-panel');
            if (firstPanel) firstPanel.classList.add('io-panel--active');
        } else {
            // No tabs - activate whichever panel is present
            const panel = $root.querySelector('.io-panel');
            if (panel) panel.classList.add('io-panel--active');
        }
    }

    // TABS

    function bindTabs() {
        if (!$tabBtns.length) return;

        $tabBtns.forEach(btn=> {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                $tabBtns.forEach(b=> {
                    b.classList.remove('io-tab-btn--active');
                    b.setAttribute('aria-selected', 'false');
                });

                $panels.forEach(p=>p.classList.remove('io-panel--active'));

                btn.classList.add('io-tab-btn--active');
                btn.setAttribute('aria-selected', 'true');

                const panel = $root.querySelector(`#tab-panel-${target}`);
                if (panel) panel.classList.add('io-panel--active');
            });
        });
    }

    // CALCULATOR
    function bindCalculator() {
        if (!$oneRM) return;

        // Lift select
        if ($liftSelect) {
            $liftSelect.addEventListener('change', ()=>{
                const isCustom = $liftSelect.value === 'custom...';
                $liftCustom.style.display = isCustom ? 'block' : 'none';
                state.lift = isCustom ? ($liftCustom.value || 'Custom') : $liftSelect.value;
                renderTable();
            });
        }
        if ($liftCustom) {
            $liftCustom.addEventListener('input', () => {
                state.lift = $liftCustom.value || 'Custom';
                renderTable();
            });
        }

        // 1RM input - live update
        $oneRM.addEventListener('input', () => {
            state.oneRM = $oneRM.value;
            renderTable;
        });

        // Unit toggle
        $unitBtns.forEach(btn => {
            btn.addEventListener('click', () =>{
                $unitBtns.forEach(b=>b.classList.remove('io-unit-btn--active'));
                btn.classList.add('io-unit-btn--active');
                state.unit = btn.dataset.unit;
                updateWeightHeader();
                renderTable();
            });
        });

        // Rounding toggle
        $roundBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                $roundBtns.forEach( b=> b.classList.remove('io-round-btn--active'));
                btn.classList.add('io-round-btn--active');
                state.round = parseFloat(btn.dataset.round);
                renderTable();
            });
        });

        // Copy
        if ($copyBtn) {
            $copyBtn.addEventListener('click', copyTable);
        }
    }

    function updateWeightHeader() {
        if ($tableWeightHeader) {
            $tableWeightHeader.textContent = `Weight (${state.unit})`;
        }
    }

    function roundTo(value, increment) {
        if (!increment) return value;
        return Math.round(value / increment) * increment;
    }

    function renderTable() {
        const raw = parseFloat(state.oneRM);
        if(!$resultWrap) return;

        if (!raw || raw<= 0) {
            $resultWrap.classList.remove('is-visible');
            return;
        }

        if ($resultTitle) {
            $resultTitle.textContent = `${state.lift} - 1RM: ${raw} ${state.unit}`;
        }

        let html = '';
        PERCENTAGES.forEach(pct => {
            const calculated = raw * (pct/100);
            const rounded = roundTo(calculated, state.round);
            const zone = ZONES[pct];
            const isHeavy = pct >= 80;

            html += `
                <tr data-zone="${zone.cls}">
                    <td>${pct}%</td>
                    <td class="io-td-weight${isHeavy ? ' io-td-weight--highlight' : ''}">${rounded}</td>
                    <td><span class="io-zone-tag io-zone-tag--${zone.cls}">${zone.label}</span></td>
                </tr>`;
        });

        $tableBody.innerHTML = html;
        $resultWrap.classList.add('is-visible');
        updateWeightHeader();
    }

    function copyTable() {
        const raw = parseFloat(state.oneRM);
        if (!raw) return;

        let text = `${state.lift} - 1RM: ${raw} ${state.unit}\n`;
        text += `${'-'.repeat(30)}\n`;
        text += `%\tWeight (${state.unit})\tZone\n`;

        PERCENTAGES.forEach(pct=>{
            const calculated = raw * (pct / 100);
            const rounded = roundTo(calculated, state.round);
            const zone = ZONES[pct];
            text += `${pct}%\t${rounded}\t${zone.label}\n`;
        });

        navigator.clipboard.writeText(text).then(()=> {
            $copyBtn.classList.add('copied');
            const span = $copyBtn.querySelector('span');
            const orig = span ? span.textContent : '';
            if (span) span.textContent = 'Copied!';
            setTimeout(()=> {
                $copyBtn.classList.remove('copied');
                if (span) span.textContent = orig;
            }, 1800);
        }).catch(()=>{
            // Fallback for older browsers
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:absolute;left:-9999px;top:-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        });
    }

    // BENCHMARK VAULT  
    function bindVault() {
        if ( !$benchmarkCards.length ) return ;

        // Accordion toggles
        $benchmarkCards.forEach(card => {
            const trigger   = card.querySelector('.io-card-trigger');
            const body  = card.querySelector('.io-card-body');
            if (!trigger || !body) return;

            trigger.addEventListener( 'click', () => {
                const expanded  = trigger.getAttribute('aria-expanded') === 'true';
                trigger.setAttribute('aria-expanded', String(!expanded));

                if(expanded) {
                    body.classList.remove('is-open');
                    // Delay hidden so animation can complete
                    setTimeout(()=> {body.hidden = true; }, 160);
                } else {
                    body.hidden = false;
                    // Force reflow then add class
                    void body.offsetWidth;
                    body.classList.add('is-open');
                }
            });
        });

        // Search + filter
        if ($search)    $search.addEventListener('input', filterVault);
        if ($catFilter) $catFilter.addEventListener('change', filterVault);
    }

    function filterVault() {
        const q     = ($search      ? $search.value.toLowerCase().trim()    : '');
        const cat   = ($catFilter   ? $catFilter.value                      : '');

        let visible = 0;

        $benchmarkCards.forEach(card=> {
            const   searchHaystack  = card.dataset.search   || '';
            const   cardCat         = card.dataset.category || '';

            const   matchesSearch   = !q  ||  searchHaystack.includes(q);
            const   matchesCat      = !cat||  cardCat  ===  cat;

            if (matchesSearch && matchesCat) {
                card.classList.remove('is-hidden');
                visible++;
            } else {
                card.classList.add('is-hidden');
            }
        });

        // Update count
        if ($benchmarkCount) {
            $benchmarkCount.textContent = visible === 1
                ? '1 Benchmark'
                : `${visible} Benchmarks`;
        }

        // Show empty message if needed
        if ($emptyMsg) {
            $emptyMsg.hidden = visible > 0;
        }
    }

    // BOOT
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();