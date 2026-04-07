document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        stocks: {
            chemg: null,
            chemr: null,
            chemb: null,
            chemy: null,
            chemp: null,
           
        },
        prices: {
            chemg: null,
            chemr: null,
            chemb: null,
            chemy: null,
            chemp: null,
           
        },
        items: {
            chemg: { name: 'Chemical G', type: 'per wl', icon: '/images/science/chem/g.png' },
            chemr: { name: 'Chemical R', type: 'per wl', icon: '/images/science/chem/r.png' },
            chemb: { name: 'Chemical B', type: 'per wl', icon: '/images/science/chem/b.png' },
            chemy: { name: 'Chemical Y', type: 'per wl', icon: '/images/science/chem/y.png' },
            chemp: { name: 'Chemical P', type: 'per wl', icon: '/images/science/chem/p.png' },
        },

        recipes: {
            slime: { name: 'Slime', chemg: 15, chemr: 0, chemb: 4, chemy: 0, chemp: 2, chemm: 0, icon: '/images/science/slime.png' },
            chemm: { name: 'Mysterious Chemical', chemg: 0, chemr: 0, chemb: 10, chemy: 20, chemp: 5, chemm: 0, icon: '/images/science/chem/m.png' },
            forcefield: { name: 'Forcefield', chemg: 20, chemr: 10, chemb: 0, chemy: 0, chemp: 0, chemm: 1, icon: '/images/science/forcefield.png' },
            fuelpack: { name: 'Fuel Pack', chemg: 0, chemr: 10, chemb: 2, chemy: 0, chemp: 0, chemm: 1, icon: '/images/science/fuel_pack.png' },
            blockglue: { name: 'Block Glue', chemg: 0, chemr: 1, chemb: 0, chemy: 0, chemp: 1, chemm: 1, icon: '/images/science/glue.png' },
        },

        selectedCraftItem: null,
        craftingPrices: {
            slime: null,
            chemm: null,
            forcefield: null,
            fuelpack: null,
            blockglue: null,
        },

        getChemicalStocks() {
            return {
                chemg: this.stocks.chemg || 0,
                chemr: this.stocks.chemr || 0,
                chemb: this.stocks.chemb || 0,
                chemy: this.stocks.chemy || 0,
                chemp: this.stocks.chemp || 0,
                chemm: 0 // Mysterious Chemical is crafted
            };
        },

        canCraftItem(recipeKey, chemicalStocks = null) {
            const stocks = chemicalStocks || this.getChemicalStocks();
            const recipe = this.recipes[recipeKey];
            
            if (!recipe) return 0;

            let canMake = Infinity;

            // Check each ingredient
            for (let chem in recipe) {
                if (chem === 'name') continue;
                const needed = recipe[chem];
                if (needed > 0) {
                    const available = stocks[chem] || 0;
                    canMake = Math.min(canMake, Math.floor(available / needed));
                }
            }

            return canMake === Infinity ? 0 : canMake;
        },

        // Calculate how many Chem M can be made from current stocks
        canCraftChemM() {
            return this.canCraftItem('chemm');
        },

        getRemainingStocks(recipeKey, quantity) {
            const stocks = JSON.parse(JSON.stringify(this.getChemicalStocks()));
            const recipe = this.recipes[recipeKey];

            if (!recipe) return stocks;

            for (let chem in recipe) {
                if (chem !== 'name' && stocks[chem] !== undefined) {
                    stocks[chem] -= recipe[chem] * quantity;
                }
            }

            return stocks;
        },

        getCraftingProfit(recipeKey) {
            const sellingPrice = parseFloat(this.craftingPrices[recipeKey]) || 0;
            if (sellingPrice <= 0) return 0;

            const canMake = this.canCraftItem(recipeKey);
            return Math.floor(canMake * sellingPrice);
        },

        formatCraftingProfit(recipeKey) {
            return `${this.getCraftingProfit(recipeKey).toFixed(1).replace(/\.0$/, '')} WL`;
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



        formatTotalRevenue() {
            return `${this.totalRevenue.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        formatNetProfit() {
            return `${this.netProfit.toFixed(1).replace(/\.0$/, '')} WL`;
        },

        resetCalculator() {
            for (let key in this.stocks) this.stocks[key] = null;
            for (let key in this.prices) this.prices[key] = null;
            for (let key in this.craftingPrices) this.craftingPrices[key] = null;
            this.selectedCraftItem = null;
        }
    }));
});
