{{-- Variant picker modal + shared add-to-cart helpers (libasHandleAdd / libasCartAdd) --}}
<div class="vo-backdrop" id="voBackdrop" aria-hidden="true">
    <div class="vo-modal" role="dialog" aria-modal="true" aria-labelledby="voName">
        <button type="button" class="vo-close" id="voClose" aria-label="Close">&times;</button>
        <h4 class="vo-title" id="voName"></h4>

        <div class="vo-group" id="voSizes">
            <span class="vo-label">Size <em>*</em></span>
            <div class="vo-row" id="voSizeRow"></div>
        </div>

        <div class="vo-group" id="voColors">
            <span class="vo-label">Color <em>*</em></span>
            <div class="vo-row" id="voColorRow"></div>
        </div>

        <div class="vo-group">
            <span class="vo-label">Quantity</span>
            <div class="vo-qty">
                <button type="button" id="voQtyMinus"><i class="fas fa-minus"></i></button>
                <input type="number" id="voQty" value="1" min="1" max="99">
                <button type="button" id="voQtyPlus"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <p class="vo-error" id="voError" style="display:none"></p>
        <button type="button" class="vo-confirm" id="voConfirm">
            <i class="fas fa-shopping-bag"></i> Add to Cart
        </button>
    </div>
</div>

<style>
.vo-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(20, 16, 10, .55);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.vo-backdrop.open { display: flex; }

.vo-modal {
    background: #fff;
    border-radius: 14px;
    max-width: 400px;
    width: 100%;
    padding: 24px;
    position: relative;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
    animation: vo-in .25s ease;
}
@keyframes vo-in { from { transform: translateY(16px); opacity: 0; } to { transform: none; opacity: 1; } }

.vo-close {
    position: absolute;
    top: 10px; right: 14px;
    border: none;
    background: none;
    font-size: 26px;
    color: #a39a82;
    cursor: pointer;
    line-height: 1;
}
.vo-close:hover { color: #1d1912; }

.vo-title { margin: 0 30px 16px 0; font-size: 17px; color: #1d1912; }

.vo-group { margin-bottom: 14px; }
.vo-group.hidden { display: none; }
.vo-label { display: block; font-size: 12px; font-weight: 600; color: #7a6126; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 8px; }
.vo-label em { color: #e63950; font-style: normal; }
.vo-row { display: flex; flex-wrap: wrap; gap: 8px; }

.vo-chip {
    padding: 8px 16px;
    border: 1.5px solid #e5dcc4;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: all .15s;
}
.vo-chip:hover { border-color: #c9a24b; }
.vo-chip.sel { border-color: #9c7c33; background: #faf6ec; color: #9c7c33; font-weight: 600; }

.vo-swatch {
    width: 36px; height: 36px;
    border-radius: 50%;
    border: 2px solid #e5dcc4;
    cursor: pointer;
    position: relative;
    transition: all .15s;
}
.vo-swatch.sel { border-color: #9c7c33; box-shadow: 0 0 0 2px #fff inset, 0 0 0 3px #9c7c33; }

.vo-qty { display: inline-flex; align-items: center; border: 1.5px solid #e5dcc4; border-radius: 8px; overflow: hidden; }
.vo-qty button { width: 36px; height: 38px; border: none; background: #faf6ec; cursor: pointer; color: #9c7c33; font-size: 13px; }
.vo-qty button:hover { background: #f3e8cd; }
.vo-qty input { width: 50px; height: 38px; border: none; text-align: center; font-size: 15px; font-weight: 600; color: #1d1912; }

.vo-error { display: flex; gap: 6px; align-items: center; color: #e63950; font-size: 13px; margin: 0 0 10px; }

.vo-confirm {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #d4b25f, #9c7c33);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .15s;
}
.vo-confirm:hover { background: linear-gradient(135deg, #c9a24b, #8a6d2f); }
.vo-confirm:disabled { background: #ccc; cursor: not-allowed; }
</style>

<script>
(function () {
    var backdrop  = document.getElementById('voBackdrop');
    var voName    = document.getElementById('voName');
    var voError   = document.getElementById('voError');
    var voQty     = document.getElementById('voQty');
    var voConfirm = document.getElementById('voConfirm');
    var state     = { productId: null, btn: null, sizes: [], colors: [], sizeId: null, colorId: null };

    function csrfToken() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    window.libasBadgeUpdate = function (count) {
        var badge = document.querySelector('.cart-count-badge');
        var link  = document.querySelector('.cart-link');
        if (count > 0) {
            if (!badge && link) {
                badge = document.createElement('span');
                badge.className = 'cart-count-badge';
                link.appendChild(badge);
            }
            if (badge) badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    };

    window.libasToast = function (message, type) {
        var box = document.querySelector('.toast-container');
        if (!box) {
            box = document.createElement('div');
            box.className = 'toast-container';
            document.body.appendChild(box);
            var s = document.createElement('style');
            s.textContent = '.toast-container{position:fixed;top:20px;right:20px;z-index:10001}'
                + '.toast{background:#fff;padding:12px 20px;border-radius:8px;margin-bottom:10px;box-shadow:0 4px 12px rgba(0,0,0,.15);display:flex;align-items:center;gap:10px;animation:libasSlide .3s ease}'
                + '.toast.success{border-left:4px solid #10b981}.toast.error{border-left:4px solid #ef4444}.toast.info{border-left:4px solid #c9a24b}'
                + '@keyframes libasSlide{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
            document.head.appendChild(s);
        }
        var t = document.createElement('div');
        t.className = 'toast ' + (type || 'info');
        var icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
        t.innerHTML = '<i class="fas fa-' + icon + '"></i><span>' + message + '</span>';
        box.appendChild(t);
        setTimeout(function () { t.remove(); }, 3000);
    };

    // ── Wishlist heart toggle (all product cards) ──
    window.libasWishlistToggle = function (productId, btn) {
        var icon = btn.querySelector('i');
        var active = btn.classList.contains('active');
        var url = (window.LIBAS_BASE || '') + (active ? '/wishlist/remove/' : '/wishlist/add/') + productId;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (r) {
            if (r.status === 401) {
                libasToast('উইশলিস্টে রাখতে লগইন করুন', 'error');
                setTimeout(function () { window.location.href = (window.LIBAS_BASE || '') + '/login'; }, 1200);
                return null;
            }
            return r.json();
        }).then(function (d) {
            if (!d) return;
            if (d.success || (d.message && /already/i.test(d.message))) {
                var nowActive = !active;
                btn.classList.toggle('active', nowActive);
                if (icon) icon.className = nowActive ? 'fas fa-heart' : 'far fa-heart';
                libasToast(nowActive ? 'উইশলিস্টে যোগ হয়েছে' : 'উইশলিস্ট থেকে সরানো হয়েছে', nowActive ? 'success' : 'error');
            } else {
                libasToast(d.message || 'সমস্যা হয়েছে', 'error');
            }
        }).catch(function () { libasToast('সমস্যা হয়েছে', 'error'); });
    };

    window.libasCartAdd = function (productId, payload, btn) {
        if (btn) {
            btn.disabled = true;
            btn.dataset.orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        }
        return fetch((window.LIBAS_BASE || '') + '/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload || { quantity: 1 })
        }).then(function (r) { return r.json(); })
          .then(function (data) {
            if (data && data.success && data.redirect) {
                window.location.href = data.redirect;
                return data;
            }
            if (data && data.success) {
                libasBadgeUpdate(data.cartCount);
                libasToast('Product added to cart!', 'success');
                if (typeof fbq !== 'undefined' && data.pixelEvent) {
                    fbq('track', 'AddToCart', data.pixelEvent.data, {eventID: data.pixelEvent.event_id});
                }
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Added!';
                    setTimeout(function () { btn.innerHTML = btn.dataset.orig; btn.disabled = false; }, 2000);
                }
            } else {
                libasToast('Could not add to cart', 'error');
                if (btn) { btn.innerHTML = btn.dataset.orig; btn.disabled = false; }
            }
            return data;
        }).catch(function () {
            libasToast('Something went wrong', 'error');
            if (btn) { btn.innerHTML = btn.dataset.orig; btn.disabled = false; }
        });
    };

    window.libasHandleAdd = function (productId, btn, checkout, preset) {
        if (btn) btn.disabled = true;
        fetch((window.LIBAS_BASE || '') + '/products/' + productId + '/options', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (opts) {
                var hasOptions = (opts.sizes && opts.sizes.length) || (opts.colors && opts.colors.length);
                if (!hasOptions) {
                    if (btn) btn.disabled = false;
                    libasCartAdd(productId, { quantity: 1, redirect_to_checkout: checkout ? 1 : undefined }, btn);
                } else {
                    if (btn) btn.disabled = false;
                    openVariantModal(productId, opts, btn, checkout, preset);
                }
            })
            .catch(function () {
                if (btn) btn.disabled = false;
                libasToast('Could not load product options', 'error');
            });
    };

    function buildChips(row, items, hexKey, onPick, selectedId) {
        row.innerHTML = '';
        items.forEach(function (item) {
            var el = document.createElement('button');
            el.type = 'button';
            if (hexKey && item[hexKey]) {
                el.className = 'vo-swatch';
                el.style.background = item[hexKey];
                el.title = item.name;
            } else {
                el.className = 'vo-chip';
                el.textContent = item.name + (item.price_adjustment > 0 ? ' (+' + item.price_adjustment + ')' : '');
            }
            if (selectedId && item.id === selectedId) el.classList.add('sel');
            el.addEventListener('click', function () {
                row.querySelectorAll('button').forEach(function (b) { b.classList.remove('sel'); });
                el.classList.add('sel');
                onPick(item);
            });
            row.appendChild(el);
        });
    }

    function openVariantModal(productId, opts, btn, checkout, preset) {
        state = { productId: productId, btn: btn, checkout: !!checkout, sizes: opts.sizes || [], colors: opts.colors || [], sizeId: (preset && preset.sizeId) || null, colorId: (preset && preset.colorId) || null };
        voName.textContent = opts.name || 'Select options';
        voError.style.display = 'none';
        voQty.value = 1;
        voConfirm.innerHTML = state.checkout
            ? '<i class="fas fa-bolt"></i> Order Now'
            : '<i class="fas fa-shopping-bag"></i> Add to Cart';

        document.getElementById('voSizes').classList.toggle('hidden', !state.sizes.length);
        document.getElementById('voColors').classList.toggle('hidden', !state.colors.length);
        buildChips(document.getElementById('voSizeRow'), state.sizes, null, function (i) { state.sizeId = i.id; }, state.sizeId);
        buildChips(document.getElementById('voColorRow'), state.colors, 'hex_code', function (i) { state.colorId = i.id; }, state.colorId);

        backdrop.classList.add('open');
        backdrop.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        backdrop.classList.remove('open');
        backdrop.setAttribute('aria-hidden', 'true');
    }

    voConfirm.addEventListener('click', function () {
        if (state.sizes.length && !state.sizeId) {
            voError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please select a size';
            voError.style.display = 'flex';
            return;
        }
        if (state.colors.length && !state.colorId) {
            voError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please select a color';
            voError.style.display = 'flex';
            return;
        }
        closeModal();
        libasCartAdd(state.productId, {
            quantity: Math.max(1, parseInt(voQty.value) || 1),
            size_id: state.sizeId,
            color_id: state.colorId,
            redirect_to_checkout: state.checkout ? 1 : undefined
        }, state.btn);
    });

    document.getElementById('voClose').addEventListener('click', closeModal);
    backdrop.addEventListener('click', function (e) { if (e.target === backdrop) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    document.getElementById('voQtyMinus').addEventListener('click', function () { voQty.value = Math.max(1, (parseInt(voQty.value) || 1) - 1); });
    document.getElementById('voQtyPlus').addEventListener('click', function () { voQty.value = Math.min(99, (parseInt(voQty.value) || 1) + 1); });
})();
</script>
