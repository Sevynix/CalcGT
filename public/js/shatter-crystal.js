document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({

        // ─── Crystal prices & types (cost inputs) ────────────────────────
        crystalPrices: { red: 0, green: 0, blue: 0, white: 0, black: 0 },
        crystalTypes: { red: 'wl/item', green: 'wl/item', blue: 'wl/item', white: 'wl/item', black: 'wl/item' },

        // ─── Per-item sell prices (keyed by item key) ─────────────────────
        prices: {},

        // ─── Selected item & how many crafts ─────────────────────────────
        selectedCraftItem: null,
        numCrafts: 1,

        // ─── Item Database ────────────────────────────────────────────────
        // Each item has: name, img (filename without .png), qty, type, recipe
        // type: 'wl/item' | 'per wl' | '200/?'
        // recipe: { red, green, blue, white, black }
        items: {
            // ── Wings & Skirts ────────────────────────────────────────────
            redCrystalWings:    { name: 'Red Crystal Wings',      img: '2254', qty: 1, type: 'wl/item', recipe: { red:2, green:0, blue:0, white:3, black:0 } },
            goldenCrystalWings: { name: 'Golden Crystal Wings',   img: '2262', qty: 1, type: 'wl/item', recipe: { red:1, green:0, blue:1, white:3, black:0 } },
            goldenCrystalSkirt: { name: 'Golden Crystal Skirt',   img: '2942', qty: 1, type: 'wl/item', recipe: { red:2, green:0, blue:3, white:0, black:0 } },
            greenCrystalWings:  { name: 'Green Crystal Wings',    img: '2258', qty: 1, type: 'wl/item', recipe: { red:0, green:2, blue:0, white:3, black:0 } },
            aquaCrystalWings:   { name: 'Aqua Crystal Wings',     img: '2264', qty: 1, type: 'wl/item', recipe: { red:0, green:1, blue:1, white:3, black:0 } },
            aquaCrystalSkirt:   { name: 'Aqua Crystal Skirt',     img: '2944', qty: 1, type: 'wl/item', recipe: { red:0, green:2, blue:3, white:0, black:0 } },
            blueCrystalWings:   { name: 'Blue Crystal Wings',     img: '2256', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:2, white:3, black:0 } },
            purpleCrystalWings: { name: 'Purple Crystal Wings',   img: '2260', qty: 1, type: 'wl/item', recipe: { red:1, green:1, blue:0, white:3, black:0 } },
            purpleCrystalSkirt: { name: 'Purple Crystal Skirt',   img: '2940', qty: 1, type: 'wl/item', recipe: { red:3, green:2, blue:0, white:0, black:0 } },
            blackCrystalDragon: { name: 'Black Crystal Dragon',   img: '2212', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:0, white:0, black:5 } },

            // ── Accessories ───────────────────────────────────────────────
            spikeyAnimeHair:       { name: 'Spikey Anime Hair',        img: '2972', qty: 1, type: 'wl/item', recipe: { red:2, green:2, blue:0, white:1, black:0 } },
            crystalSkull:          { name: 'Crystal Skull',            img: '2268', qty: 1, type: 'wl/item', recipe: { red:2, green:2, blue:1, white:0, black:0 } },
            crystalGlaive:         { name: 'Crystal Glaive',           img: '2266', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:2, white:1, black:2 } },
            demonicArm:            { name: 'Demonic Arm',              img: '2754', qty: 1, type: 'wl/item', recipe: { red:2, green:0, blue:0, white:0, black:3 } },
            theGungnir:            { name: 'The Gungnir',              img: '2756', qty: 1, type: 'wl/item', recipe: { red:1, green:0, blue:0, white:3, black:1 } },
            emeraldChoker:         { name: 'Emerald Choker',           img: '2706', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:3, white:2, black:0 } },
            crystalSmithingTools:  { name: 'Crystal Smithing Tools',   img: '3180', qty: 1, type: 'wl/item', recipe: { red:1, green:1, blue:1, white:0, black:2 } },
            dnaExtractor:          { name: 'DNA Extractor',            img: '4124', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:0, white:2, black:3 } },
            amethystChoker:        { name: 'Amethyst Choker',          img: '5268', qty: 1, type: 'wl/item', recipe: { red:3, green:0, blue:0, white:2, black:0 } },
            staticHair:            { name: 'Static Hair',              img: '5266', qty: 1, type: 'wl/item', recipe: { red:3, green:0, blue:1, white:1, black:0 } },
            luminousEyes:          { name: 'Luminous Eyes',            img: '6848', qty: 1, type: 'wl/item', recipe: { red:0, green:0, blue:0, white:1, black:4 } },
            crystalShardNecklace:  { name: 'Crystal Shard Necklace',   img: '6820', qty: 1, type: 'wl/item', recipe: { red:0, green:3, blue:0, white:2, black:0 } },

            // ── Blocks & Decor ────────────────────────────────────────────
            crystalTree:           { name: 'Crystal Tree',             img: '2272', qty: 3,  type: 'wl/item', recipe: { red:0, green:3, blue:2, white:0, black:0 } },
            crystalSpikes:         { name: 'Crystal Spikes',           img: '2276', qty: 5,  type: 'wl/item', recipe: { red:2, green:3, blue:0, white:0, black:0 } },
            crystalClock:          { name: 'Crystal Clock',            img: '2252', qty: 1,  type: 'wl/item', recipe: { red:0, green:1, blue:2, white:2, black:0 } },
            crystalGate:           { name: 'Crystal Gate',             img: '2274', qty: 1,  type: 'wl/item', recipe: { red:3, green:1, blue:0, white:1, black:0 } },
            stainedGlass:          { name: 'Stained Glass',            img: '2270', qty: 10, type: 'per wl',  recipe: { red:1, green:2, blue:2, white:0, black:0 } },
            shiftyBlock:           { name: 'Shifty Block',             img: '2590', qty: 5,  type: 'wl/item', recipe: { red:2, green:1, blue:1, white:1, black:0 } },
            sapphireBlock:         { name: 'Sapphire Block',           img: '2812', qty: 1,  type: 'wl/item', recipe: { red:0, green:5, blue:0, white:0, black:0 } },
            yellowDiamond:         { name: 'Yellow Diamond',           img: '3424', qty: 1,  type: 'wl/item', recipe: { red:2, green:0, blue:2, white:1, black:0 } },
            steamLamp:             { name: 'Steam Lamp',               img: '3770', qty: 3,  type: 'wl/item', recipe: { red:2, green:1, blue:2, white:0, black:0 } },
            gildedFrame:           { name: 'Gilded Frame',             img: '3526', qty: 20, type: 'per wl',  recipe: { red:3, green:0, blue:2, white:0, black:0 } },
            goldenCaveCrystal:     { name: 'Golden Cave Crystal',      img: '3582', qty: 1,  type: 'wl/item', recipe: { red:2, green:0, blue:2, white:0, black:1 } },
            amethystBlock:         { name: 'Amethyst Block',           img: '4762', qty: 1,  type: 'wl/item', recipe: { red:2, green:2, blue:0, white:0, black:1 } },
            plasmaGlobe:           { name: 'Plasma Globe',             img: '5204', qty: 1,  type: 'wl/item', recipe: { red:2, green:0, blue:1, white:2, black:0 } },
            harmonicChimes:        { name: 'Harmonic Chimes',          img: '5106', qty: 1,  type: 'wl/item', recipe: { red:1, green:2, blue:0, white:2, black:0 } },
            celestialKaleidoscope: { name: 'Celestial Kaleidoscope',   img: '5104', qty: 1,  type: 'wl/item', recipe: { red:1, green:1, blue:1, white:2, black:0 } },
            kaleidoscopicWallpaper:{ name: 'Kaleidoscopic Wallpaper',  img: '5272', qty: 10, type: 'per wl',  recipe: { red:1, green:1, blue:2, white:1, black:0 } },
            demonCrystal:          { name: 'Demon Crystal',            img: '5274', qty: 1,  type: 'wl/item', recipe: { red:3, green:0, blue:1, white:0, black:1 } },
            opalBlock:             { name: 'Opal Block',               img: '5270', qty: 1,  type: 'wl/item', recipe: { red:1, green:3, blue:1, white:0, black:0 } },
            celestialFrame:        { name: 'Celestial Frame',          img: '5280', qty: 10, type: 'per wl',  recipe: { red:1, green:1, blue:3, white:0, black:0 } },

            // ── Event ─────────────────────────────────────────────────────
            heartstone: { name: "Heartstone (Valentine's Week)", img: '392', qty: 1, type: 'wl/item', recipe: { red:3, green:0, blue:0, white:1, black:1 } },
        },

        // ─── localStorage ─────────────────────────────────────────────────
        init() {
            const key = 'gthelp-shatter-crystal';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.crystalPrices) Object.assign(this.crystalPrices, data.crystalPrices);
                    if (data.crystalTypes) Object.assign(this.crystalTypes, data.crystalTypes);
                    if (data.prices) Object.assign(this.prices, data.prices);
                    if (data.items) {
                        for (let k in data.items) {
                            if (this.items[k] && data.items[k].type !== undefined) {
                                this.items[k].type = data.items[k].type;
                            }
                        }
                    }
                    if (data.selectedCraftItem !== undefined) this.selectedCraftItem = data.selectedCraftItem;
                    if (data.numCrafts !== undefined) this.numCrafts = data.numCrafts;
                }
            } catch(e) {}
            setInterval(() => this._save(key), 1000);
        },

        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    crystalPrices: this.crystalPrices,
                    crystalTypes: this.crystalTypes,
                    prices: this.prices,
                    items: this.items,
                    selectedCraftItem: this.selectedCraftItem,
                    numCrafts: this.numCrafts,
                }));
            } catch(e) {}
        },

        // ─── Single crystal cost based on type ───────────────────────────
        getSingleCrystalCost(color) {
            const val = parseFloat(this.crystalPrices[color]);
            if (!val || val <= 0) return 0;
            const type = this.crystalTypes[color];
            
            if (type === 'per wl')  return 1 / val;
            if (type === '200/?')   return val / 200;
            if (type === 'wl/item') return val;
            return 0;
        },

        // ─── Total cost to craft the item ────────────────────────────────
        getItemCost(key) {
            const r = this.items[key].recipe;
            return (r.red   * this.getSingleCrystalCost('red'))
                 + (r.green * this.getSingleCrystalCost('green'))
                 + (r.blue  * this.getSingleCrystalCost('blue'))
                 + (r.white * this.getSingleCrystalCost('white'))
                 + (r.black * this.getSingleCrystalCost('black'));
        },

        // ─── Revenue from selling qty items, scaled by numCrafts ─────────
        getItemRevenue(key, crafts) {
            const item = this.items[key];
            const val  = parseFloat(this.prices[key]);
            if (!val || val <= 0) return 0;

            const n = crafts !== undefined ? crafts : (this.numCrafts || 1);
            const totalItems = item.qty * n;
            if (totalItems <= 0) return 0;

            if (item.type === 'per wl')  return Math.floor(totalItems / val);
            if (item.type === '200/?')   return Math.floor((totalItems / 200) * val);
            if (item.type === 'wl/item') return Math.floor(totalItems * val);
            return 0;
        },

        // ─── Totals ───────────────────────────────────────────────────────
        get totalRevenue() {
            let rev = 0;
            for (let key in this.items) {
                rev += this.getItemRevenue(key);
            }
            return rev;
        },

        get netProfit() {
            if (!this.selectedCraftItem) return 0;
            const n    = this.numCrafts || 1;
            const rev  = this.getItemRevenue(this.selectedCraftItem, n);
            const cost = this.getItemCost(this.selectedCraftItem) * n;
            return parseFloat((rev - cost).toFixed(2));
        },

        get profitPercentage() {
            if (!this.selectedCraftItem) return '+0.0%';
            const n    = this.numCrafts || 1;
            const cost = this.getItemCost(this.selectedCraftItem) * n;
            if (cost === 0) return this.netProfit > 0 ? '+100.0%' : '+0.0%';
            const pct  = (this.netProfit / cost) * 100;
            const sign = pct > 0 ? '+' : '';
            return `${sign}${pct.toFixed(1)}%`;
        },

        // ─── Formatters ───────────────────────────────────────────────────
        formatWL(val) {
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            if (Number.isInteger(n)) return n.toString();
            return n.toFixed(2);
        },

        // ─── Resets ───────────────────────────────────────────────────────
        resetTable() {
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = 'wl/item';
            }
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-shatter-crystal'); } catch(e) {}
            this.crystalPrices = { red: 0, green: 0, blue: 0, white: 0, black: 0 };
            this.crystalTypes = { red: 'wl/item', green: 'wl/item', blue: 'wl/item', white: 'wl/item', black: 'wl/item' };
            this.prices = {};
            this.selectedCraftItem = null;
            this.numCrafts = 1;
            for (let key in this.items) {
                this.items[key].type = 'wl/item';
            }
        },
    }));
});
