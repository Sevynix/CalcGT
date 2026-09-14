document.addEventListener('alpine:init', () => {
    Alpine.data('fishTraining', () => ({

        // ─── State ────────────────────────────────────────────────────────────
        selectedCreature: null,
        pfishPrice: null,
        pfishPriceType: 'wl/fish',   // 'wl/fish' | 'fish/wl'
        numTrainings: 1,
        selectedFoodIdx: null,
        foodLbPerWl: null,
        foodPriceType: 'item/wl',    // 'item/wl' | 'wl/item' (for xpPerItem foods like Oil Slick)
        sellPrice: null,

        // ─── XP to reach max (level 1 → 10) ─────────────────────────────────
        XP_TO_MAX: 5325,

        // ─── Creature Database ────────────────────────────────────────────────
        creatures: {
            // ── Fish ──────────────────────────────────────────────────────────
            goldfish:             { name: 'Goldfish',                         id: 3000,  type: 'fish', food: [{name:'Sunfish',      xpPerLb:0.8 }, {name:'Catfish',                      xpPerLb:2.5 }, {name:'Bass',                                xpPerLb:1.20}] },
            picassoTriggerfish:   { name: 'Picasso Triggerfish',              id: 3544,  type: 'fish', food: [{name:'Gar',          xpPerLb:0.6 }, {name:'Catfish',                      xpPerLb:1.3 }, {name:'Bass',                                xpPerLb:1.3 }] },
            whale:                { name: 'Whale',                            id: 3024,  type: 'fish', food: [{name:'Goldfish',     xpPerLb:1.8 }, {name:'Weird Manta Ray',               xpPerLb:6   }, {name:'Stonefish',                           xpPerLb:1.08}] },
            dogfish:              { name: 'Dogfish',                          id: 3030,  type: 'fish', food: [{name:'Sunfish',      xpPerLb:1   }, {name:'Stonefish',                     xpPerLb:1.5 }, {name:'Catfish',                             xpPerLb:3   }] },
            mahiMahi:             { name: 'Mahi Mahi',                        id: 3034,  type: 'fish', food: [{name:'Gar',          xpPerLb:1.1 }, {name:'Sunfish',                       xpPerLb:1.69}, {name:'Goldfish',                            xpPerLb:1.8 }] },
            bass:                 { name: 'Bass',                             id: 3038,  type: 'fish', food: [{name:'Mahi Mahi',   xpPerLb:1.5 }, {name:'Catfish',                      xpPerLb:3   }, {name:'Dogfish',                             xpPerLb:3   }] },
            sunfish:              { name: 'Sunfish',                          id: 3036,  type: 'fish', food: [{name:'Dogfish',     xpPerLb:1.5 }, {name:'Bass',                          xpPerLb:1.5 }, {name:'Mahi Mahi',                           xpPerLb:2   }] },
            catfish:              { name: 'Catfish',                          id: 3026,  type: 'fish', food: [{name:'Mahi Mahi',   xpPerLb:1.1 }, {name:'Sunfish',                       xpPerLb:1.5 }, {name:'Goldfish',                            xpPerLb:2   }] },
            weirdMantaRay:        { name: 'Weird Manta Ray',                  id: 5580,  type: 'fish', food: [{name:'Whale',        xpPerLb:1.2 }, {name:'Sand Shark',                    xpPerLb:1.8 }, {name:'Alpha Great White Shark',              xpPerLb:5   }] },
            alphaGWS:             { name: 'Alpha Great White Shark',          id: 5574,  type: 'fish', food: [{name:'Stonefish',    xpPerLb:0.33}, {name:'Whale',                         xpPerLb:1.4 }, {name:'Cuttlefish',                          xpPerLb:8   }] },
            seahorse:             { name: 'Seahorse',                         id: 3438,  type: 'fish', food: [{name:'Catfish',      xpPerLb:1.1 }, {name:'Cuttlefish',                    xpPerLb:8   }, {name:'Maidmare Fish',                       xpPerLb:8   }] },
            gar:                  { name: 'Gar',                              id: 3032,  type: 'fish', food: [{name:'Sand Shark',   xpPerLb:1.5 }, {name:'Whale',                         xpPerLb:2   }, {name:'Maidmare Fish',                       xpPerLb:6   }] },
            mutantFish:           { name: 'Mutant Fish',                      id: 3440,  type: 'fish', food: [{name:'Stonefish',    xpPerLb:0.4 }, {name:'Weird Manta Ray',               xpPerLb:8   }, {name:'Absolutely Real Elephant Fish',        xpPerLb:15  }] },
            sandShark:            { name: 'Sand Shark',                       id: 4958,  type: 'fish', food: [{name:'Gar',          xpPerLb:0.5 }, {name:'Catfish',                      xpPerLb:1   }, {name:'Cuttlefish',                          xpPerLb:8   }] },
            stonefish:            { name: 'Stonefish',                        id: 3814,  type: 'fish', food: [{name:'Gar',          xpPerLb:0.5 }, {name:'Whale',                         xpPerLb:1.5 }, {name:'Sand Shark',                          xpPerLb:2   }] },
            cuttlefish:           { name: 'Cuttlefish',                       id: 5552,  type: 'fish', food: [{name:'Gar',          xpPerLb:0.5 }, {name:'Maidmare Fish',                 xpPerLb:8   }, {name:'Sand Shark',                          xpPerLb:2   }] },
            elephantFish:         { name: 'Absolutely Real Elephant Fish',    id: 5548,  type: 'fish', food: [{name:'Maidmare Fish',xpPerLb:8   }, {name:'Catfish',                      xpPerLb:1.5 }, {name:'Bass',                                xpPerLb:0.8 }] },
            angelDevilFish:       { name: 'Double-Headed Angel-Devil Fish',   id: 5542,  type: 'fish', food: [{name:'Sand Shark',   xpPerLb:1.5 }, {name:'Whale',                         xpPerLb:2   }, {name:'Weird Manta Ray',                     xpPerLb:6   }] },
            maidmareFish:         { name: 'Maidmare Fish',                    id: 5538,  type: 'fish', food: [{name:'Weird Manta Ray',xpPerLb:6.33},{name:'Alpha Great White Shark',      xpPerLb:8   }] },
            rainbowfish:          { name: 'Rainbowfish',                      id: 3434,  type: 'fish', food: [{name:'Mahi Mahi',   xpPerLb:0.7 }, {name:'Dogfish',                       xpPerLb:1.1 }, {name:'Cuttlefish',                          xpPerLb:8   }] },
            bunnyfish:            { name: 'Totally Legit Bunnyfish',          id: 3436,  type: 'fish', food: [{name:'Goldfish',     xpPerLb:0.5 }, {name:'Stonefish',                     xpPerLb:0.8 }, {name:'Absolutely Real Elephant Fish',        xpPerLb:15  }] },
            axolotl:              { name: 'Axolotl',                          id: 3550,  type: 'fish', food: [{name:'Sunfish',      xpPerLb:0.3 }, {name:'Catfish',                      xpPerLb:0.7 }, {name:'Weird Manta Ray',                     xpPerLb:9   }] },
            pineappleFish:        { name: 'Pineapple Fish',                   id: 3620,  type: 'fish', food: [{name:'Dogfish',     xpPerLb:0.25}, {name:'Mahi Mahi',                     xpPerLb:0.25}, {name:'Sand Shark',                          xpPerLb:1   }] },
            dragonTurtle:         { name: 'Dragon Turtle',                    id: 7744,  type: 'fish', food: [{name:'Alpha Great White Shark',xpPerLb:10},{name:'Antarctic Icefish',       xpPerLb:50  }] },
            turkeyFish:           { name: 'Turkey Fish',                      id: 10264, type: 'fish', food: [{name:'Goldfish',     xpPerLb:0.6 }, {name:'Sand Shark',                    xpPerLb:1   }, {name:'Gingerbread Fish',                    xpPerLb:20  }] },
            skeletalShark:        { name: 'Skeletal Shark',                   id: 3092,  type: 'fish', food: [{name:'Gar',          xpPerLb:0.5 }, {name:'Sand Shark',                    xpPerLb:1.7 }, {name:'Alpha Great White Shark',              xpPerLb:7   }] },
            demonAngler:          { name: 'Demon Angler',                     id: 3096,  type: 'fish', food: [{name:'Weird Manta Ray',xpPerLb:5  }, {name:'Cuttlefish',                    xpPerLb:10  }] },
            mrNarwhal:            { name: 'Mr. Narwhal',                      id: 5448,  type: 'fish', food: [{name:'Swedish Fish',  xpPerLb:12  }, {name:'Gingerbread Fish',               xpPerLb:15  }, {name:'Neon Squid',                          xpPerLb:20  }] },
            firefin:              { name: 'Firefin',                          id: 3746,  type: 'fish', food: [{name:'Stonefish',    xpPerLb:0.5 }, {name:'Cuttlefish',                    xpPerLb:10  }, {name:'Double-Headed Angel-Devil Fish',       xpPerLb:15  }] },
            koiFish:              { name: 'Koi Fish',                         id: 5100,  type: 'fish', food: [{name:'Alpha Great White Shark',xpPerLb:8},{name:'Weird Manta Ray',          xpPerLb:10  }] },
            xenosNebulafin:       { name: "Xenoso's Nebulafin",               id: 16318, type: 'fish', food: [{name:'Maidmare',     xpPerLb:1   }, {name:'Double-Headed Angel-Devil Fish',xpPerLb:3   }, {name:'Dungeonist Crab',                     xpPerLb:8   }] },
            clockworkFish:        { name: 'Clockwork Fish',                   id: 8460,  type: 'fish', food: [{name:'Oil Slick',    xpPerItem:100}] },

            // ── Crabs ─────────────────────────────────────────────────────────
            crawdad:              { name: 'Crawdad',                          id: 3458,  type: 'crab', food: [{name:'Purple Shore Crab', xpPerLb:2.25}, {name:'Lobster',         xpPerLb:3   }, {name:'Hermit Crab',     xpPerLb:10 }] },
            purpleShoreCrab:      { name: 'Purple Shore Crab',                id: 3452,  type: 'crab', food: [{name:'Lobster',           xpPerLb:2.3 }, {name:'Crawdad',         xpPerLb:4   }, {name:'Hermit Crab',     xpPerLb:10 }] },
            dungeonistCrab:       { name: 'Dungeonist Crab',                  id: 3456,  type: 'crab', food: [{name:'Crawdad',           xpPerLb:1   }, {name:'Purple Shore Crab',xpPerLb:3  }, {name:'Hermit Crab',     xpPerLb:2.5}] },
            lobster:              { name: 'Lobster',                          id: 3450,  type: 'crab', food: [{name:'Alaskan King Crab', xpPerLb:1   }, {name:'Crawdad',         xpPerLb:2   }, {name:'Hermit Crab',     xpPerLb:20 }] },
            hermitCrab:           { name: 'Hermit Crab',                      id: 3460,  type: 'crab', food: [{name:'Crawdad',           xpPerLb:2   }, {name:'Purple Shore Crab',xpPerLb:2  }, {name:'Dungeonist Crab', xpPerLb:65 }] },
            alaskanKingCrab:      { name: 'Alaskan King Crab',                id: 3454,  type: 'crab', food: [{name:'Lobster',           xpPerLb:1.8 }, {name:'Alaskan King Crab',xpPerLb:12.9},{name:'Dungeonist Crab',xpPerLb:50 }] },
        },

        // ─── localStorage ─────────────────────────────────────────────────────
        init() {
            const key = 'gthelp-fish-training';
            try {
                const saved = localStorage.getItem(key);
                if (saved) {
                    const d = JSON.parse(saved);
                    if (d.selectedCreature  !== undefined) this.selectedCreature  = d.selectedCreature;
                    if (d.pfishPrice        !== undefined) this.pfishPrice        = d.pfishPrice;
                    if (d.pfishPriceType    !== undefined) this.pfishPriceType    = d.pfishPriceType;
                    if (d.numTrainings      !== undefined) this.numTrainings      = d.numTrainings;
                    if (d.selectedFoodIdx   !== undefined) this.selectedFoodIdx   = d.selectedFoodIdx;
                    if (d.foodLbPerWl       !== undefined) this.foodLbPerWl       = d.foodLbPerWl;
                    if (d.foodPriceType     !== undefined) this.foodPriceType     = d.foodPriceType;
                    if (d.sellPrice         !== undefined) this.sellPrice         = d.sellPrice;
                }
            } catch(e) {}
            setInterval(() => this._save(key), 1500);
        },

        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    selectedCreature: this.selectedCreature,
                    pfishPrice:       this.pfishPrice,
                    pfishPriceType:   this.pfishPriceType,
                    numTrainings:     this.numTrainings,
                    selectedFoodIdx:  this.selectedFoodIdx,
                    foodLbPerWl:      this.foodLbPerWl,
                    foodPriceType:    this.foodPriceType,
                    sellPrice:        this.sellPrice,
                }));
            } catch(e) {}
        },

        // ─── Helpers ──────────────────────────────────────────────────────────
        get fishList() {
            return Object.entries(this.creatures).filter(([,v]) => v.type === 'fish');
        },
        get crabList() {
            return Object.entries(this.creatures).filter(([,v]) => v.type === 'crab');
        },

        get selectedData() {
            return this.selectedCreature ? this.creatures[this.selectedCreature] : null;
        },

        get selectedFood() {
            if (!this.selectedData || this.selectedFoodIdx === null) return null;
            return this.selectedData.food[this.selectedFoodIdx] ?? null;
        },

        // ─── Per-fish cost (WL) ───────────────────────────────────────────────
        get pfishCostEach() {
            const v = parseFloat(this.pfishPrice);
            if (!v || v <= 0) return 0;
            if (this.pfishPriceType === 'fish/wl') return 1 / v;   // lb per wl → WL per fish
            return v;                                                // wl/fish
        },

        // ─── XP ──────────────────────────────────────────────────────────────
        get xpPerTraining() {
            return this.XP_TO_MAX; // always 5325 for full max
        },

        get totalXP() {
            const n = parseInt(this.numTrainings) || 0;
            return this.xpPerTraining * n;
        },

        // ─── LB / Items needed ────────────────────────────────────────────────
        get lbPerTraining() {
            const food = this.selectedFood;
            if (!food) return 0;
            if (food.xpPerItem) {
                // returns items needed (not lb)
                return Math.ceil(this.XP_TO_MAX / food.xpPerItem);
            }
            const xpLb = parseFloat(food.xpPerLb);
            if (!xpLb || xpLb <= 0) return 0;
            return this.XP_TO_MAX / xpLb;
        },

        get totalLb() {
            const n = parseInt(this.numTrainings) || 0;
            return this.lbPerTraining * n;
        },

        // ─── Food cost (WL) ──────────────────────────────────────────────────
        get foodCostTotal() {
            const food = this.selectedFood;
            if (!food) return 0;
            const qty = parseFloat(this.foodLbPerWl);
            if (!qty || qty <= 0) return 0;

            if (food.xpPerItem) {
                // Oil Slick and similar: priced per item
                const items = this.totalLb; // reused as items count
                if (this.foodPriceType === 'wl/item') return items * qty;
                return items / qty; // item/wl
            }

            // Normal lb-based food
            if (!this.totalLb) return 0;
            return this.totalLb / qty;
        },

        // ─── P-fish buy cost ─────────────────────────────────────────────────
        get pfishBuyCostTotal() {
            const n = parseInt(this.numTrainings) || 0;
            return this.pfishCostEach * n;
        },

        // ─── Total cost ──────────────────────────────────────────────────────
        get totalCost() {
            return this.pfishBuyCostTotal + this.foodCostTotal;
        },

        // ─── Income ──────────────────────────────────────────────────────────
        get income() {
            const n    = parseInt(this.numTrainings) || 0;
            const sell = parseFloat(this.sellPrice);
            if (!sell || sell <= 0 || n <= 0) return 0;
            return sell * n;
        },

        // ─── Net Profit ──────────────────────────────────────────────────────
        get netProfit() {
            return parseFloat((this.income - this.totalCost).toFixed(2));
        },

        get profitMargin() {
            if (this.totalCost === 0) return this.income > 0 ? '+100.0%' : '0.0%';
            const pct  = (this.netProfit / this.totalCost) * 100;
            const sign = pct >= 0 ? '+' : '';
            return `${sign}${pct.toFixed(1)}%`;
        },

        // ─── Formatters ───────────────────────────────────────────────────────
        fmt(val) {
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            if (Number.isInteger(n)) return n.toLocaleString('en-US', { useGrouping: false });
            return n.toLocaleString('en-US', { useGrouping: false, maximumFractionDigits: 2 });
        },

        // WL must be whole numbers — round away from zero so costs/losses are never understated
        fmtWl(val) {
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            const rounded = n >= 0 ? Math.ceil(n) : Math.floor(n);
            return rounded.toLocaleString('en-US', { useGrouping: false });
        },

        fmtLb(val) {
            if (val === null) return '0';
            const n = parseFloat(val);
            if (isNaN(n)) return '0';
            return n % 1 === 0 ? n.toLocaleString('en-US', { useGrouping: false }) : n.toLocaleString('en-US', { useGrouping: false, maximumFractionDigits: 1 });
        },

        // ─── Food Image Lookup ────────────────────────────────────────────────
        getFoodImage(food) {
            // If the food entry already has an explicit id use it
            if (food.id) return '/images/fishTraining/' + food.id + '.png';
            const map = {
                'Goldfish': 3000, 'Picasso Triggerfish': 3544, 'Whale': 3024,
                'Dogfish': 3030, 'Mahi Mahi': 3034, 'Bass': 3038,
                'Sunfish': 3036, 'Catfish': 3026, 'Weird Manta Ray': 5580,
                'Alpha Great White Shark': 5574, 'Seahorse': 3438, 'Gar': 3032,
                'Mutant Fish': 3440, 'Sand Shark': 4958, 'Stonefish': 3814,
                'Cuttlefish': 5552, 'Absolutely Real Elephant Fish': 5548,
                'Double-Headed Angel-Devil Fish': 5542, 'Maidmare Fish': 5538,
                'Maidmare': 5540, 'Rainbowfish': 3434, 'Totally Legit Bunnyfish': 3436,
                'Axolotl': 3550, 'Pineapple Fish': 3620, 'Dragon Turtle': 7744,
                'Turkey Fish': 10264, 'Skeletal Shark': 3092, 'Demon Angler': 3096,
                'Mr. Narwhal': 5448, 'Firefin': 3746, 'Koi Fish': 5100,
                "Xenoso's Nebulafin": 16318, 'Clockwork Fish': 8460,
                'Swedish Fish': 3226, 'Gingerbread Fish': 3224, 'Neon Squid': 3220,
                'Crawdad': 3458, 'Purple Shore Crab': 3452, 'Dungeonist Crab': 3456,
                'Lobster': 3450, 'Hermit Crab': 3460, 'Alaskan King Crab': 3454,
                'Oil Slick': 7158,
            };
            const id = map[food.name];
            return id ? '/images/fishTraining/' + id + '.png' : null;
        },

        // ─── Reset ────────────────────────────────────────────────────────────
        resetCalculator() {
            try { localStorage.removeItem('gthelp-fish-training'); } catch(e) {}
            this.selectedCreature = null;
            this.pfishPrice       = null;
            this.pfishPriceType   = 'wl/fish';
            this.numTrainings     = 1;
            this.selectedFoodIdx  = null;
            this.foodLbPerWl      = null;
            this.foodPriceType    = 'item/wl';
            this.sellPrice        = null;
        },
    }));
});
