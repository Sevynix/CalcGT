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
            slime: { name: 'Slime', chemg: 15, chemr: 0, chemb: 4, chemy: 0, chemp: 2, chemm: 0, yield: 4, icon: '/images/science/slime.png' },
            chemm: { name: 'Mysterious Chemical', chemg: 0, chemr: 0, chemb: 10, chemy: 20, chemp: 5, chemm: 0, yield: 1, icon: '/images/science/chem/m.png' },
            forcefield: { name: 'Forcefield', chemg: 20, chemr: 10, chemb: 0, chemy: 0, chemp: 0, chemm: 1, yield: 1, icon: '/images/science/forcefield.png' },
            fuelpack: { name: 'Fuel Pack', chemg: 10, chemr: 0, chemb: 2, chemy: 0, chemp: 0, chemm: 1, yield: 5, icon: '/images/science/fuel_pack.png' },
            blockglue: { name: 'Block Glue', chemg: 0, chemr: 1, chemb: 0, chemy: 0, chemp: 1, chemm: 1, yield: 10, icon: '/images/science/glue.png' },
        },

        selectedCraftItem: null,
        craftingPrices: {
            slime: null,
            chemm: null,
            forcefield: null,
            fuelpack: null,
            blockglue: null,
        },
        craftingPriceTypes: {
            slime: 'wl/item',
            chemm: 'wl/item',
            forcefield: 'wl/item',
            fuelpack: 'wl/item',
            blockglue: 'wl/item',
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

        getFlattenedRecipe(recipeKey) {
            const recipe = this.recipes[recipeKey];
            if (!recipe) return {};

            let actualNeeded = { 
                chemg: recipe.chemg || 0,
                chemr: recipe.chemr || 0,
                chemb: recipe.chemb || 0,
                chemy: recipe.chemy || 0,
                chemp: recipe.chemp || 0
            };

            if (recipe.chemm && recipe.chemm > 0) {
                const chemmNeeded = recipe.chemm;
                const chemmRecipe = this.recipes.chemm;
                actualNeeded.chemg += chemmNeeded * (chemmRecipe.chemg || 0);
                actualNeeded.chemr += chemmNeeded * (chemmRecipe.chemr || 0);
                actualNeeded.chemb += chemmNeeded * (chemmRecipe.chemb || 0);
                actualNeeded.chemy += chemmNeeded * (chemmRecipe.chemy || 0);
                actualNeeded.chemp += chemmNeeded * (chemmRecipe.chemp || 0);
            }

            return actualNeeded;
        },

        canCraftItem(recipeKey, chemicalStocks = null) {
            const stocks = chemicalStocks || this.getChemicalStocks();
            const flattened = this.getFlattenedRecipe(recipeKey);
            const recipe = this.recipes[recipeKey];
            
            let maxOps = Infinity;
            let hasRequirements = false;

            for (let chem in flattened) {
                const needed = flattened[chem];
                if (needed > 0) {
                    hasRequirements = true;
                    const available = stocks[chem] || 0;
                    maxOps = Math.min(maxOps, Math.floor(available / needed));
                }
            }

            if (!hasRequirements || maxOps === Infinity) return 0;
            return maxOps * (recipe.yield || 1);
        },

        // Calculate how many Chem M can be made from current stocks
        canCraftChemM() {
            return this.canCraftItem('chemm');
        },

        getLeftoverBaseChemicals(recipeKey) {
            const stocks = this.getChemicalStocks();
            const flattened = this.getFlattenedRecipe(recipeKey);
            const recipe = this.recipes[recipeKey];

            // Calculate how many operations we can perform
            const maxCrafts = this.canCraftItem(recipeKey);
            const ops = maxCrafts / (recipe.yield || 1);

            let leftover = {};
            for (let chem in stocks) {
                if (chem === 'chemm') continue; // We only want to show base chemical leftovers
                const used = (flattened[chem] || 0) * ops;
                leftover[chem] = Math.max(0, stocks[chem] - used);
            }
            return leftover;
        },

        getCraftingProfit(recipeKey) {
            const sellingPrice = parseFloat(this.craftingPrices[recipeKey]) || 0;
            const priceType = this.craftingPriceTypes[recipeKey] || 'wl/item';
            if (sellingPrice <= 0) return 0;

            const canMake = this.canCraftItem(recipeKey);
            
            if (priceType === 'per wl') {
                return (canMake / sellingPrice);
            } else if (priceType === '200/?') {
                return ((canMake / 200) * sellingPrice);
            } else if (priceType === 'wl/item') {
                return (canMake * sellingPrice);
            }
            return 0;
        },

        formatCraftingProfit(recipeKey) {
            return `${this.getCraftingProfit(recipeKey).toFixed(1).replace(/\.0$/, '')} WL`;
        },

        init() {
            const key = 'gthelp-science-provider';
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
                    if (data.enabledCategories) Object.assign(this.enabledCategories, data.enabledCategories);
                }
            } catch(e) {}
            // Save every second — $watch doesn't detect deep object changes
            setInterval(() => this._save(key), 1000);
        },
        _save(key) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    items: this.items,
                    numPacks: this.numPacks,
                    wlCostPerPack: this.wlCostPerPack,
                    prices: this.prices,
                    stocks: this.stocks || {},
                    enabledCategories: this.enabledCategories || {},
                }));
            } catch(e) {}
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

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-science-provider'); } catch(e) {}
            for (let key in this.stocks) this.stocks[key] = null;
            for (let key in this.prices) this.prices[key] = null;
            for (let key in this.craftingPrices) this.craftingPrices[key] = null;
            this.selectedCraftItem = null;
        }
    }));
});
