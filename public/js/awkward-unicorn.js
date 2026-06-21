document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 0,
        wlCostPerPack: 0,
        stocks: {
            clouds: null,
            dreamstoneBlock: null,
            rainbowBlock: null,
            rainbowWig: null,
            enchantedSpatula: null,
            happyUnicornBlock: null,
            angryUnicornBlock: null,
            unicornJumper: null,
            horseMask: null,
            scrollBulletin: null,
            veryBadUnicorn: null,
            unicornHorn: null,
            teddyBear: null,
            giftOfTheUnicorn: null,
            retroLegWarmers: null,
            rainbowScarf: null,
            twintailHair: null,
            growmojiCoolShadesMask: null,
            cartoonGloveHatRed: null,
            cartoonGloveHatBlue: null,
            that90sHair: null,
            freakyFriedEggEyes: null,
            peasInAPodHat: null,
            petPinkCrocodile: null,
            deathTopStompers: null,
        },
        prices: {
            clouds: null,
            dreamstoneBlock: null,
            rainbowBlock: null,
            rainbowWig: null,
            enchantedSpatula: null,
            happyUnicornBlock: null,
            angryUnicornBlock: null,
            unicornJumper: null,
            horseMask: null,
            scrollBulletin: null,
            veryBadUnicorn: null,
            unicornHorn: null,
            teddyBear: null,
            giftOfTheUnicorn: null,
            retroLegWarmers: null,
            rainbowScarf: null,
            twintailHair: null,
            growmojiCoolShadesMask: null,
            cartoonGloveHatRed: null,
            cartoonGloveHatBlue: null,
            that90sHair: null,
            freakyFriedEggEyes: null,
            peasInAPodHat: null,
            petPinkCrocodile: null,
            deathTopStompers: null,
        },
        items: {
            clouds:                { name: 'Clouds',                    type: 'wl/item',   icon: '/images/unicorn/image_30.png' },
            dreamstoneBlock:       { name: 'Dreamstone Block',           type: 'wl/item',   icon: '/images/unicorn/image_31.png' },
            rainbowBlock:          { name: 'Rainbow Block',              type: 'wl/item',   icon: '/images/unicorn/image_32.png' },
            rainbowWig:            { name: 'Rainbow Wig',                type: 'wl/item',   icon: '/images/unicorn/image_33.png' },
            enchantedSpatula:      { name: 'Enchanted Spatula',          type: 'wl/item',   icon: '/images/unicorn/image_34.png' },
            happyUnicornBlock:     { name: 'Happy Unicorn Block',        type: 'wl/item',  icon: '/images/unicorn/image_35.png' },
            angryUnicornBlock:     { name: 'Angry Unicorn Block',        type: 'wl/item',  icon: '/images/unicorn/image_36.png' },
            unicornJumper:         { name: 'Unicorn Jumper',             type: 'wl/item',  icon: '/images/unicorn/image_37.png' },
            horseMask:             { name: 'Horse Mask',                 type: 'wl/item',  icon: '/images/unicorn/image_38.png' },
            scrollBulletin:        { name: 'Scroll Bulletin',            type: 'wl/item',  icon: '/images/unicorn/image_39.png' },
            veryBadUnicorn:        { name: 'Very Bad Unicorn',           type: 'wl/item', icon: '/images/unicorn/image_40.png' },
            unicornHorn:           { name: 'Unicorn Horn',               type: 'wl/item', icon: '/images/unicorn/image_41.png' },
            teddyBear:             { name: 'Teddy Bear',                 type: 'wl/item', icon: '/images/unicorn/image_42.png' },
            giftOfTheUnicorn:      { name: 'Gift of the Unicorn',        type: 'wl/item',   icon: '/images/unicorn/image_43.png' },
            retroLegWarmers:       { name: 'Retro Leg Warmers',          type: 'wl/item',   icon: '/images/unicorn/image_44.png' },
            rainbowScarf:          { name: 'Rainbow Scarf',              type: 'wl/item',   icon: '/images/unicorn/image_45.png' },
            twintailHair:          { name: 'Twintail Hair',              type: 'wl/item',   icon: '/images/unicorn/image_46.png' },
            growmojiCoolShadesMask:{ name: 'Growmoji Cool Shades Mask',  type: 'wl/item',   icon: '/images/unicorn/image_47.png' },
            cartoonGloveHatRed:    { name: 'Cartoon Glove Hat Red',      type: 'wl/item',   icon: '/images/unicorn/image_48.png' },
            cartoonGloveHatBlue:   { name: 'Cartoon Glove Hat Blue',     type: 'wl/item',   icon: '/images/unicorn/image_49.png' },
            that90sHair:           { name: "That 90's Hair",             type: 'wl/item',   icon: '/images/unicorn/image_50.png' },
            freakyFriedEggEyes:    { name: 'Freaky Fried Egg Eyes',      type: 'wl/item',   icon: '/images/unicorn/image_51.png' },
            peasInAPodHat:         { name: 'Peas in a Pod Hat',          type: 'wl/item',   icon: '/images/unicorn/image_52.png' },
            petPinkCrocodile:      { name: 'Pet Pink Crocodile',         type: 'wl/item',   icon: '/images/unicorn/image_53.png' },
            deathTopStompers:      { name: 'Death Top Stompers',         type: 'wl/item',   icon: '/images/unicorn/image_54.png' },
        },

        init() {
            const key = 'gthelp-awkward-unicorn';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.items) {
                        for (let k in data.items) {
                            if (this.items && this.items[k] && data.items[k].type !== undefined) {
                                this.items[k].type = data.items[k].type;
                            }
                        }
                    }
                    if (data.numPacks !== undefined) this.numPacks = data.numPacks;
                    if (data.wlCostPerPack !== undefined) this.wlCostPerPack = data.wlCostPerPack;
                    if (data.prices) Object.assign(this.prices, data.prices);
                    if (data.stocks) Object.assign(this.stocks, data.stocks);
                }
            } catch(e) {}
            // Save every second - $watch doesn't detect deep object changes
            setInterval(() => this._save(key), 1000);
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                    stocks: this.stocks,
                }));
            } catch(e) {}
        },

        get totalCost() {
            return (this.numPacks || 0) * (this.wlCostPerPack || 0);
        },

        getItemRevenue(key) {
            let item = this.items[key];
            let val = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            let totalItems = this.stocks[key] || 0;
            if (totalItems <= 0) return 0;

            if (item.type === 'per wl') {
                return Math.floor(totalItems / val);
            } else if (item.type === '200/?') {
                return Math.floor((totalItems / 200) * val);
            } else if (item.type === 'wl/item') {
                return Math.floor(totalItems * val);
            }
            return 0;
        },

        get totalRevenue() {
            let rev = 0;
            for (let key in this.items) {
                rev += this.getItemRevenue(key);
            }
            return rev;
        },

        get netProfit() {
            return parseFloat((this.totalRevenue - this.totalCost).toFixed(2));
        },

        get marginNumeric() {
            let cost = this.totalCost;
            if (cost === 0) return this.netProfit > 0 ? 100 : 0;
            return (this.netProfit / cost) * 100;
        },

        get profitPercentage() {
            let pct = this.marginNumeric;
            let sign = pct > 0 ? '+' : '';
            return `${sign}${pct.toFixed(1)}%`;
        },

        formatTotalCost() {
            return `${this.totalCost.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        formatTotalRevenue() {
            return `${this.totalRevenue.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        formatNetProfit() {
            return `${this.netProfit.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-awkward-unicorn'); } catch(e) {}
            this.numPacks = 0;
            this.wlCostPerPack = 0;
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = 'wl/item';
            }
        }
    }));
});
