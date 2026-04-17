document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 1,
        wlCostPerPack: 0,
        stocks: {
            flameOn: null,
            heatVision: null,
            incinerate: null,
            liquify: null,
            overheat: null,
            frostBreath: null,
            frozenMirror: null,
            iceBarrier: null,
            iceShards: null,
            puddle: null,
            megawattPulse: null,
            overcharge: null,
            resuscitate: null,
            shockingFist: null,
            thunderstorm: null,
            crush: null,
            enrage: null,
            regeneration: null,
            superSpeed: null,
            superStrength: null,
            crimeWave: null,
        },
        prices: {
            flameOn: null,
            heatVision: null,
            incinerate: null,
            liquify: null,
            overheat: null,
            frostBreath: null,
            frozenMirror: null,
            iceBarrier: null,
            iceShards: null,
            puddle: null,
            megawattPulse: null,
            overcharge: null,
            resuscitate: null,
            shockingFist: null,
            thunderstorm: null,
            crush: null,
            enrage: null,
            regeneration: null,
            superSpeed: null,
            superStrength: null,
            crimeWave: null,
        },
        items: {
            crimeWave: { name: 'Crime Wave', type: 'wl/item', icon: '/images/crime/wave.png' },

            flameOn: { name: 'Superpower - Flame On!', type: 'per wl', icon: '/images/crime/fire/flame.png' },
            heatVision: { name: 'Superpower - Heat Vision', type: 'per wl', icon: '/images/crime/fire/heat.png' },
            incinerate: { name: 'Superpower - Incinerate', type: 'per wl', icon: '/images/crime/fire/inci.png' },
            liquify: { name: 'Superpower - Liquify', type: 'per wl', icon: '/images/crime/fire/liq.png' },
            overheat: { name: 'Superpower - Overheat', type: 'per wl', icon: '/images/crime/fire/over.png' },

            frostBreath: { name: 'Superpower - Frost Breath', type: 'per wl', icon: '/images/crime/ice/frost.png' },
            frozenMirror: { name: 'Superpower - Frozen Mirror', type: 'per wl', icon: '/images/crime/ice/mirror.png' },
            iceBarrier: { name: 'Superpower - Ice Barrier', type: 'per wl', icon: '/images/crime/ice/barrier.png' },
            iceShards: { name: 'Superpower - Ice Shards', type: 'per wl', icon: '/images/crime/ice/shard.png' },
            puddle: { name: 'Superpower - Puddle', type: 'per wl', icon: '/images/crime/ice/puddle.png' },

            megawattPulse: { name: 'Superpower - Megawatt Pulse', type: 'per wl', icon: '/images/crime/lightning/mega.png' },
            overcharge: { name: 'Superpower - Overcharge', type: 'per wl', icon: '/images/crime/lightning/over.png' },
            resuscitate: { name: 'Superpower - Resuscitate', type: 'per wl', icon: '/images/crime/lightning/res.png' },
            shockingFist: { name: 'Superpower - Shocking Fist', type: 'per wl', icon: '/images/crime/lightning/shock.png' },
            thunderstorm: { name: 'Superpower - Thunderstorm', type: 'per wl', icon: '/images/crime/lightning/thunder.png' },

            crush: { name: 'Superpower - Crush!', type: 'per wl', icon: '/images/crime/muscle/crush.png' },
            enrage: { name: 'Superpower - Enrage', type: 'per wl', icon: '/images/crime/muscle/enrage.png' },
            regeneration: { name: 'Superpower - Regeneration', type: 'per wl', icon: '/images/crime/muscle/rege.png' },
            superSpeed: { name: 'Superpower - Super Speed', type: 'per wl', icon: '/images/crime/muscle/speed.png' },
            superStrength: { name: 'Superpower - Super Strength', type: 'per wl', icon: '/images/crime/muscle/strength.png' },
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

        get profitPercentage() {
            let cost = this.totalCost;
            if (cost === 0) return this.netProfit > 0 ? '+100.0%' : '+0.0%';
            let pct = (this.netProfit / cost) * 100;
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

        resetCalculator() {
            this.numPacks = 1;
            this.wlCostPerPack = 0;
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = (key === 'crimeWave') ? 'wl/item' : 'per wl';
            }
        }
    }));
});
