document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 0,
        wlCostPerPack: 0,
        stocks: {
            balloonThrowingStandItem: null,
            bouncyBalloonItem: null,
            dunkingBucketItem: null,
            kiddiePoolItem: null,
            inflatableRingItem: null,
            targetItem: null,
            waterslideItem: null,
            waterslideStrutItem: null,
            partyBlockItem: null,
            partyBuntingItem: null,
            adobeBlockItem: null,
            churroBlockItem: null,
            cornFieldLadderItem: null,
            nachoBlockItem: null,
            olmecHeadItem: null,
            pinataBlockItem: null,
            wrestlingRingItem: null,
            castleStoneItem: null,
            castleStoneBackgroundItem: null,
            castleTurretItem: null,
            manorHouseSandstoneItem: null,
            manorHouseSandstoneBackgroundItem: null,
            noblemansHouseItem: null,
            stablesItem: null,
            artWallItem: null,
            audioGearItem: null,
            audioRackItem: null,
            cloudsBackgroundItem: null,
            cloudsWallpaperItem: null,
            darkAquaWallpaperItem: null,
            darkBlueWallpaperItem: null,
            darkBrownWallpaperItem: null,
            darkGreenWallpaperItem: null,
            darkGreyWallpaperItem: null,
            darkOrangeWallpaperItem: null,
            darkPurpleWallpaperItem: null,
            darkWalnutBlockItem: null,
            darkWalnutLaddersItem: null,
            darkWalnutWallItem: null,
            darkYellowWallpaperItem: null,
            dinosaurWallpaperItem: null,
            eZCookOvenItem: null,
            innieBlockItem: null,
            jadeBlockItem: null,
            outieBlockItem: null,
            picketFenceItem: null,
            skullWallpaperItem: null,
            sheetMusicBassNoteItem: null,
            sheetMusicDrumsItem: null,
            sheetMusicPianoNoteItem: null,
            sheetMusicSaxNoteItem: null,
            beachUmbrellaItem: null,
            cowSkullItem: null,
            deckChairItem: null,
            flamingoItem: null,
            sandBucketItem: null,
            sandtopianItem: null,
            blackLeatherCouchItem: null,
            chimneyItem: null,
            conveyorBlockItem: null,
            creepyBabyBlockItem: null,
            creepyBabyGateItem: null,
            digitalDirtItem: null,
            graniteBlockBlueItem: null,
            graniteBlockGreenItem: null,
            graniteBlockRedItem: null,
            graniteColumnItem: null,
            grimstoneBackgroundItem: null,
            growmojiSmileBlockItem: null,
            growmojiTongueBlockItem: null,
            growmojiWowBlockItem: null,
            happyBlockItem: null,
            lavaFallsItem: null,
            mahoganyFrameItem: null,
            regalBannisterItem: null,
            regalStairsItem: null,
            retroTapeBlockItem: null,
            ricketyLaddersItem: null,
            shiningBlockPurpleItem: null,
            shiningBlockRedItem: null,
            shiningBlockYellowItem: null,
            slickBlueBlockItem: null,
            slickGreenBlockItem: null,
            slickRedBlockItem: null,
            buddyofStoneStatueItem: null,
            cottonCandyCloudsItem: null,
            fountainofLoveItem: null,
            loveLadderItem: null,
            toweringHairItem: null,
            ceremonialAltarItem: null,
            flowerVaseItem: null,
            shamrockCheckpointItem: null,
            shepardsPieBlockItem: null,
            stPatricksCelebrationWallpaperGreenItem: null,
            stPatricksCelebrationWallpaperWhiteItem: null,
            theWonderfulBarnItem: null,
            candyBrickWallItem: null,
            gaekkebrevItem: null,
            sunnysideYolkfallItem: null,
            sweetBunnyCheckpointItem: null,
            tacoWindowItem: null,
            superPineappleDoorItem: null,
            summerCheckpointItem: null,
            tropicalBarItem: null,
            tropicalBarStoolItem: null,
            riceCropWallpaperItem: null,
            kookyCheckpointItem: null,
            darkChocolateBlockItem: null,
            diamondEncrustedDrapedFiligreedJadeBlockItem: null,
            drapedFiligreedJadeBlockItem: null,
            filigreedJadeBlockItem: null,
            googlyEyedDiamondEncrustedDrapedFiligreedJadeBlockItem: null,
            jigsawWallpaperItem: null,
            sheetMusicFluteNoteItem: null,
            sheetMusicSpanishGuitarNoteItem: null,
            sheetMusicViolinNoteItem: null,
        },
        prices: {
            balloonThrowingStandItem: null,
            bouncyBalloonItem: null,
            dunkingBucketItem: null,
            kiddiePoolItem: null,
            inflatableRingItem: null,
            targetItem: null,
            waterslideItem: null,
            waterslideStrutItem: null,
            partyBlockItem: null,
            partyBuntingItem: null,
            adobeBlockItem: null,
            churroBlockItem: null,
            cornFieldLadderItem: null,
            nachoBlockItem: null,
            olmecHeadItem: null,
            pinataBlockItem: null,
            wrestlingRingItem: null,
            castleStoneItem: null,
            castleStoneBackgroundItem: null,
            castleTurretItem: null,
            manorHouseSandstoneItem: null,
            manorHouseSandstoneBackgroundItem: null,
            noblemansHouseItem: null,
            stablesItem: null,
            artWallItem: null,
            audioGearItem: null,
            audioRackItem: null,
            cloudsBackgroundItem: null,
            cloudsWallpaperItem: null,
            darkAquaWallpaperItem: null,
            darkBlueWallpaperItem: null,
            darkBrownWallpaperItem: null,
            darkGreenWallpaperItem: null,
            darkGreyWallpaperItem: null,
            darkOrangeWallpaperItem: null,
            darkPurpleWallpaperItem: null,
            darkWalnutBlockItem: null,
            darkWalnutLaddersItem: null,
            darkWalnutWallItem: null,
            darkYellowWallpaperItem: null,
            dinosaurWallpaperItem: null,
            eZCookOvenItem: null,
            innieBlockItem: null,
            jadeBlockItem: null,
            outieBlockItem: null,
            picketFenceItem: null,
            skullWallpaperItem: null,
            sheetMusicBassNoteItem: null,
            sheetMusicDrumsItem: null,
            sheetMusicPianoNoteItem: null,
            sheetMusicSaxNoteItem: null,
            beachUmbrellaItem: null,
            cowSkullItem: null,
            deckChairItem: null,
            flamingoItem: null,
            sandBucketItem: null,
            sandtopianItem: null,
            blackLeatherCouchItem: null,
            chimneyItem: null,
            conveyorBlockItem: null,
            creepyBabyBlockItem: null,
            creepyBabyGateItem: null,
            digitalDirtItem: null,
            graniteBlockBlueItem: null,
            graniteBlockGreenItem: null,
            graniteBlockRedItem: null,
            graniteColumnItem: null,
            grimstoneBackgroundItem: null,
            growmojiSmileBlockItem: null,
            growmojiTongueBlockItem: null,
            growmojiWowBlockItem: null,
            happyBlockItem: null,
            lavaFallsItem: null,
            mahoganyFrameItem: null,
            regalBannisterItem: null,
            regalStairsItem: null,
            retroTapeBlockItem: null,
            ricketyLaddersItem: null,
            shiningBlockPurpleItem: null,
            shiningBlockRedItem: null,
            shiningBlockYellowItem: null,
            slickBlueBlockItem: null,
            slickGreenBlockItem: null,
            slickRedBlockItem: null,
            buddyofStoneStatueItem: null,
            cottonCandyCloudsItem: null,
            fountainofLoveItem: null,
            loveLadderItem: null,
            toweringHairItem: null,
            ceremonialAltarItem: null,
            flowerVaseItem: null,
            shamrockCheckpointItem: null,
            shepardsPieBlockItem: null,
            stPatricksCelebrationWallpaperGreenItem: null,
            stPatricksCelebrationWallpaperWhiteItem: null,
            theWonderfulBarnItem: null,
            candyBrickWallItem: null,
            gaekkebrevItem: null,
            sunnysideYolkfallItem: null,
            sweetBunnyCheckpointItem: null,
            tacoWindowItem: null,
            superPineappleDoorItem: null,
            summerCheckpointItem: null,
            tropicalBarItem: null,
            tropicalBarStoolItem: null,
            riceCropWallpaperItem: null,
            kookyCheckpointItem: null,
            darkChocolateBlockItem: null,
            diamondEncrustedDrapedFiligreedJadeBlockItem: null,
            drapedFiligreedJadeBlockItem: null,
            filigreedJadeBlockItem: null,
            googlyEyedDiamondEncrustedDrapedFiligreedJadeBlockItem: null,
            jigsawWallpaperItem: null,
            sheetMusicFluteNoteItem: null,
            sheetMusicSpanishGuitarNoteItem: null,
            sheetMusicViolinNoteItem: null,
        },
        items: {
            balloonThrowingStandItem: { name: 'Balloon Throwing Stand', type: 'per wl', icon: '/images/bbm/drop/common/4920.png' },
            bouncyBalloonItem: { name: 'Bouncy Balloon', type: 'per wl', icon: '/images/bbm/drop/common/4878.png' },
            dunkingBucketItem: { name: 'Dunking Bucket', type: 'per wl', icon: '/images/bbm/drop/common/7524.png' },
            kiddiePoolItem: { name: 'Kiddie Pool', type: 'per wl', icon: '/images/bbm/drop/common/7528.png' },
            inflatableRingItem: { name: 'Inflatable Ring', type: 'per wl', icon: '/images/bbm/drop/common/7526.png' },
            targetItem: { name: 'Target', type: 'per wl', icon: '/images/bbm/drop/common/4922.png' },
            waterslideItem: { name: 'Waterslide', type: 'per wl', icon: '/images/bbm/drop/common/7522.png' },
            waterslideStrutItem: { name: 'Waterslide Strut', type: 'per wl', icon: '/images/bbm/drop/common/7520.png' },
            partyBlockItem: { name: 'Party Block', type: 'per wl', icon: '/images/bbm/drop/common/2280.png' },
            partyBuntingItem: { name: 'Party Bunting', type: 'per wl', icon: '/images/bbm/drop/common/4372.png' },
            adobeBlockItem: { name: 'Adobe Block', type: 'per wl', icon: '/images/bbm/drop/common/716.png' },
            churroBlockItem: { name: 'Churro Block', type: 'per wl', icon: '/images/bbm/drop/common/1572.png' },
            cornFieldLadderItem: { name: 'Corn Field Ladder', type: 'per wl', icon: '/images/bbm/drop/common/11898.png' },
            nachoBlockItem: { name: 'Nacho Block', type: 'per wl', icon: '/images/bbm/drop/common/2596.png' },
            olmecHeadItem: { name: 'Olmec Head', type: 'per wl', icon: '/images/bbm/drop/common/714.png' },
            pinataBlockItem: { name: 'Pinata Block', type: 'per wl', icon: '/images/bbm/drop/common/2632.png' },
            wrestlingRingItem: { name: 'Wrestling Ring', type: 'per wl', icon: '/images/bbm/drop/common/2630.png' },
            castleStoneItem: { name: 'Castle Stone', type: 'per wl', icon: '/images/bbm/drop/common/6180.png' },
            castleStoneBackgroundItem: { name: 'Castle Stone Background', type: 'per wl', icon: '/images/bbm/drop/common/6182.png' },
            castleTurretItem: { name: 'Castle Turret', type: 'per wl', icon: '/images/bbm/drop/common/6184.png' },
            manorHouseSandstoneItem: { name: 'Manor House Sandstone', type: 'per wl', icon: '/images/bbm/drop/common/7846.png' },
            manorHouseSandstoneBackgroundItem: { name: 'Manor House Sandstone Background', type: 'per wl', icon: '/images/bbm/drop/common/7848.png' },
            noblemansHouseItem: { name: 'Nobleman\'s House', type: 'per wl', icon: '/images/bbm/drop/common/7272.png' },
            stablesItem: { name: 'Stables', type: 'per wl', icon: '/images/bbm/drop/common/7284.png' },
            artWallItem: { name: 'Art Wall', type: 'per wl', icon: '/images/bbm/drop/common/334.png' },
            audioGearItem: { name: 'Audio Gear', type: 'per wl', icon: '/images/bbm/drop/common/4630.png' },
            audioRackItem: { name: 'Audio Rack', type: 'per wl', icon: '/images/bbm/drop/common/4632.png' },
            cloudsBackgroundItem: { name: 'Clouds Background', type: 'per wl', icon: '/images/bbm/drop/common/8214.png' },
            cloudsWallpaperItem: { name: 'Clouds Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8206.png' },
            darkAquaWallpaperItem: { name: 'Dark Aqua Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8228.png' },
            darkBlueWallpaperItem: { name: 'Dark Blue Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/4944.png' },
            darkBrownWallpaperItem: { name: 'Dark Brown Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8236.png' },
            darkGreenWallpaperItem: { name: 'Dark Green Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/4942.png' },
            darkGreyWallpaperItem: { name: 'Dark Grey Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8238.png' },
            darkOrangeWallpaperItem: { name: 'Dark Orange Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8234.png' },
            darkPurpleWallpaperItem: { name: 'Dark Purple Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8232.png' },
            darkWalnutBlockItem: { name: 'Dark Walnut Block', type: 'per wl', icon: '/images/bbm/drop/common/8216.png' },
            darkWalnutLaddersItem: { name: 'Dark Walnut Ladders', type: 'per wl', icon: '/images/bbm/drop/common/8220.png' },
            darkWalnutWallItem: { name: 'Dark Walnut Wall', type: 'per wl', icon: '/images/bbm/drop/common/8218.png' },
            darkYellowWallpaperItem: { name: 'Dark Yellow Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8230.png' },
            dinosaurWallpaperItem: { name: 'Dinosaur Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8210.png' },
            eZCookOvenItem: { name: 'E-Z Cook Oven', type: 'per wl', icon: '/images/bbm/drop/common/952.png' },
            innieBlockItem: { name: 'Innie Block', type: 'per wl', icon: '/images/bbm/drop/common/1110.png' },
            jadeBlockItem: { name: 'Jade Block', type: 'per wl', icon: '/images/bbm/drop/common/2708.png' },
            outieBlockItem: { name: 'Outie Block', type: 'per wl', icon: '/images/bbm/drop/common/1112.png' },
            picketFenceItem: { name: 'Picket Fence', type: 'per wl', icon: '/images/bbm/drop/common/264.png' },
            skullWallpaperItem: { name: 'Skull Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/8208.png' },
            sheetMusicBassNoteItem: { name: 'Sheet Music: Bass Note', type: 'per wl', icon: '/images/bbm/drop/common/414.png' },
            sheetMusicDrumsItem: { name: 'Sheet Music: Drums', type: 'per wl', icon: '/images/bbm/drop/common/426.png' },
            sheetMusicPianoNoteItem: { name: 'Sheet Music: Piano Note', type: 'per wl', icon: '/images/bbm/drop/common/420.png' },
            sheetMusicSaxNoteItem: { name: 'Sheet Music: Sax Note', type: 'per wl', icon: '/images/bbm/drop/common/4634.png' },
            beachUmbrellaItem: { name: 'Beach Umbrella', type: 'per wl', icon: '/images/bbm/drop/common/848.png' },
            cowSkullItem: { name: 'Cow Skull', type: 'per wl', icon: '/images/bbm/drop/common/2860.png' },
            deckChairItem: { name: 'Deck Chair', type: 'per wl', icon: '/images/bbm/drop/common/6324.png' },
            flamingoItem: { name: 'Flamingo', type: 'per wl', icon: '/images/bbm/drop/common/2862.png' },
            sandBucketItem: { name: 'Sand Bucket', type: 'per wl', icon: '/images/bbm/drop/common/13628.png' },
            sandtopianItem: { name: 'Sandtopian', type: 'per wl', icon: '/images/bbm/drop/common/13602.png' },
            blackLeatherCouchItem: { name: 'Black Leather Couch', type: 'per wl', icon: '/images/bbm/drop/common/8994.png' },
            chimneyItem: { name: 'Chimney', type: 'per wl', icon: '/images/bbm/drop/common/8990.png' },
            conveyorBlockItem: { name: 'Conveyor Block', type: 'per wl', icon: '/images/bbm/drop/common/11192.png' },
            creepyBabyBlockItem: { name: 'Creepy Baby Block', type: 'per wl', icon: '/images/bbm/drop/common/8200.png' },
            creepyBabyGateItem: { name: 'Creepy Baby Gate', type: 'per wl', icon: '/images/bbm/drop/common/10060.png' },
            digitalDirtItem: { name: 'Digital Dirt', type: 'per wl', icon: '/images/bbm/drop/common/8202.png' },
            graniteBlockBlueItem: { name: 'Granite Block - Blue', type: 'per wl', icon: '/images/bbm/drop/common/13748.png' },
            graniteBlockGreenItem: { name: 'Granite Block - Green', type: 'per wl', icon: '/images/bbm/drop/common/13750.png' },
            graniteBlockRedItem: { name: 'Granite Block - Red', type: 'per wl', icon: '/images/bbm/drop/common/13752.png' },
            graniteColumnItem: { name: 'Granite Column', type: 'per wl', icon: '/images/bbm/drop/common/8204.png' },
            grimstoneBackgroundItem: { name: 'Grimstone Background', type: 'per wl', icon: '/images/bbm/drop/common/8992.png' },
            growmojiSmileBlockItem: { name: 'Growmoji Smile Block', type: 'per wl', icon: '/images/bbm/drop/common/8224.png' },
            growmojiTongueBlockItem: { name: 'Growmoji Tongue Block', type: 'per wl', icon: '/images/bbm/drop/common/8832.png' },
            growmojiWowBlockItem: { name: 'Growmoji Wow Block', type: 'per wl', icon: '/images/bbm/drop/common/8226.png' },
            happyBlockItem: { name: 'Happy Block', type: 'per wl', icon: '/images/bbm/drop/common/11194.png' },
            lavaFallsItem: { name: 'Lava Falls', type: 'per wl', icon: '/images/bbm/drop/common/8212.png' },
            mahoganyFrameItem: { name: 'Mahogany Frame', type: 'per wl', icon: '/images/bbm/drop/common/8988.png' },
            regalBannisterItem: { name: 'Regal Bannister', type: 'per wl', icon: '/images/bbm/drop/common/8986.png' },
            regalStairsItem: { name: 'Regal Stairs', type: 'per wl', icon: '/images/bbm/drop/common/8984.png' },
            retroTapeBlockItem: { name: 'Retro Tape Block', type: 'per wl', icon: '/images/bbm/drop/common/10554.png' },
            ricketyLaddersItem: { name: 'Rickety Ladders', type: 'per wl', icon: '/images/bbm/drop/common/8222.png' },
            shiningBlockPurpleItem: { name: 'Shining Block - Purple', type: 'per wl', icon: '/images/bbm/drop/common/8828.png' },
            shiningBlockRedItem: { name: 'Shining Block - Red', type: 'per wl', icon: '/images/bbm/drop/common/8824.png' },
            shiningBlockYellowItem: { name: 'Shining Block - Yellow', type: 'per wl', icon: '/images/bbm/drop/common/8826.png' },
            slickBlueBlockItem: { name: 'Slick Blue Block', type: 'per wl', icon: '/images/bbm/drop/common/12532.png' },
            slickGreenBlockItem: { name: 'Slick Green Block', type: 'per wl', icon: '/images/bbm/drop/common/12534.png' },
            slickRedBlockItem: { name: 'Slick Red Block', type: 'per wl', icon: '/images/bbm/drop/common/12530.png' },
            buddyofStoneStatueItem: { name: 'Buddy of Stone Statue', type: 'per wl', icon: '/images/bbm/drop/valentine/13122.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            cottonCandyCloudsItem: { name: 'Cotton Candy Clouds', type: 'per wl', icon: '/images/bbm/drop/valentine/11696.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            fountainofLoveItem: { name: 'Fountain of Love', type: 'per wl', icon: '/images/bbm/drop/valentine/13146.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            loveLadderItem: { name: 'Love Ladder', type: 'per wl', icon: '/images/bbm/drop/valentine/10646.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            toweringHairItem: { name: 'Towering Hair', type: 'per wl', icon: '/images/bbm/drop/valentine/11698.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            ceremonialAltarItem: { name: 'Ceremonial Altar', type: 'per wl', icon: '/images/bbm/drop/valentine/15200.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            flowerVaseItem: { name: 'Flower Vase', type: 'per wl', icon: '/images/bbm/drop/valentine/15956.png', category: 'Valentine\'s Week' , category: 'Valentine\'s Week' },
            shamrockCheckpointItem: { name: 'Shamrock Checkpoint', type: 'per wl', icon: '/images/bbm/drop/patrick/11738.png', category: 'St. Patrick\'s Week' , category: 'St. Patrick\'s Week' },
            shepardsPieBlockItem: { name: 'Shepard\'s Pie Block', type: 'per wl', icon: '/images/bbm/drop/patrick/11732.png' , category: 'St. Patrick\'s Week' },
            stPatricksCelebrationWallpaperGreenItem: { name: 'St Patrick\'s Celebration Wallpaper Green', type: 'per wl', icon: '/images/bbm/drop/patrick/10710.png' , category: 'St. Patrick\'s Week' },
            stPatricksCelebrationWallpaperWhiteItem: { name: 'St Patrick\'s Celebration Wallpaper White', type: 'per wl', icon: '/images/bbm/drop/patrick/10708.png' , category: 'St. Patrick\'s Week' },
            theWonderfulBarnItem: { name: 'The Wonderful Barn', type: 'per wl', icon: '/images/bbm/drop/patrick/13350.png', category: 'St. Patrick\'s Week' , category: 'St. Patrick\'s Week' },
            candyBrickWallItem: { name: 'Candy Brick Wall', type: 'per wl', icon: '/images/bbm/drop/grow4good/14426.png', category: 'Grow4Good Week' , category: 'Grow4Good Week' },
            gaekkebrevItem: { name: 'Gaekkebrev', type: 'per wl', icon: '/images/bbm/drop/easter/11812.png', category: 'Easter Week' , category: 'Easter Week' },
            sunnysideYolkfallItem: { name: 'Sunnyside Yolkfall', type: 'per wl', icon: '/images/bbm/drop/easter/13384.png', category: 'Easter Week' , category: 'Easter Week' },
            tacoWindowItem: { name: 'Taco Window', type: 'per wl', icon: '/images/bbm/drop/cinco/10880.png', category: 'Cinco De Mayo Week' , category: 'Cinco De Mayo Week' },
            superPineappleDoorItem: { name: 'Super Pineapple Door', type: 'per wl', icon: '/images/bbm/drop/pinnaple/12006.png', category: 'Super Pineapple Party' , category: 'Super Pineapple Party' },
            summerCheckpointItem: { name: 'Summer Checkpoint', type: 'per wl', icon: '/images/bbm/drop/summer/9740.png', category: 'SummerFest' , category: 'SummerFest' },
            tropicalBarItem: { name: 'Tropical Bar', type: 'per wl', icon: '/images/bbm/drop/summer/11054.png', category: 'SummerFest' , category: 'SummerFest' },
            tropicalBarStoolItem: { name: 'Tropical Bar Stool', type: 'per wl', icon: '/images/bbm/drop/summer/11056.png', category: 'SummerFest' , category: 'SummerFest' },
            riceCropWallpaperItem: { name: 'Rice Crop Wallpaper', type: 'per wl', icon: '/images/bbm/drop/harvest/11278.png', category: 'Harvest Festival' , category: 'Harvest Festival' },
            kookyCheckpointItem: { name: 'Kooky Checkpoint', type: 'per wl', icon: '/images/bbm/drop/halloween/13992.png', category: 'Halloween Week' , category: 'Halloween Week' },
            darkChocolateBlockItem: { name: 'Dark Chocolate Block', type: 'per wl', icon: '/images/bbm/drop/common/2566.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            diamondEncrustedDrapedFiligreedJadeBlockItem: { name: 'Diamond-Encrusted Draped Filigreed Jade Block', type: 'per wl', icon: '/images/bbm/drop/common/2714.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            drapedFiligreedJadeBlockItem: { name: 'Draped Filigreed Jade Block', type: 'per wl', icon: '/images/bbm/drop/common/2712.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            filigreedJadeBlockItem: { name: 'Filigreed Jade Block', type: 'per wl', icon: '/images/bbm/drop/common/2710.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            googlyEyedDiamondEncrustedDrapedFiligreedJadeBlockItem: { name: 'Googly-Eyed Diamond-Encrusted Draped Filigreed Jade Block', type: 'per wl', icon: '/images/bbm/drop/common/2716.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            jigsawWallpaperItem: { name: 'Jigsaw Wallpaper', type: 'per wl', icon: '/images/bbm/drop/common/3786.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            sheetMusicFluteNoteItem: { name: 'Sheet Music: Flute Note', type: 'per wl', icon: '/images/bbm/drop/common/5726.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            sheetMusicSpanishGuitarNoteItem: { name: 'Sheet Music: Spanish Guitar Note', type: 'per wl', icon: '/images/bbm/drop/common/6030.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            sheetMusicViolinNoteItem: { name: 'Sheet Music: Violin Note', type: 'per wl', icon: '/images/bbm/drop/common/6808.png', category: 'Miscellaneous' , category: 'Miscellaneous' },
            sweetBunnyCheckpointItem: { name: 'Sweet Bunny Checkpoint', type: 'per wl', icon: '/images/bbm/drop/common/15408.png', category: 'Miscellaneous' },
        },

        enabledCategories: {},

        init() {
            const key = 'gthelp-bbm-provider';
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
            // Save every second —  doesn't detect deep object changes
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
                    enabledCategories: this.enabledCategories,
                }));
            } catch(e) {}
        },

        get categorizedItems() {
            let groups = { 'Base Items': {} };
            for (let key in this.items) {
                let item = this.items[key];
                let cat = item.category;
                if (!cat || cat === 'Miscellaneous') {
                    groups['Base Items'][key] = item;
                } else {
                    if (this.enabledCategories[cat] === undefined) {
                        this.enabledCategories[cat] = false;
                    }
                    if (this.enabledCategories[cat]) {
                        if (!groups[cat]) {
                            groups[cat] = {};
                        }
                        groups[cat][key] = item;
                    }
                }
            }
            return groups;
        },

        get availableCategories() {
            let cats = new Set();
            for (let key in this.items) {
                let cat = this.items[key].category;
                if (cat && cat !== 'Miscellaneous') {
                    cats.add(cat);
                }
            }
            return Array.from(cats).sort();
        },

        getCategoryStats(cat) {
            let count = 0;
            let icon = null;
            let rev = 0;
            for (let key in this.items) {
                if (this.items[key].category === cat) {
                    count++;
                    if (!icon) icon = this.items[key].icon;
                    if (this.enabledCategories[cat]) {
                        rev += this.getItemRevenue(key);
                    }
                }
            }
            return { count, icon, rev };
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
                let cat = this.items[key].category;
                if (cat && cat !== 'Miscellaneous' && !this.enabledCategories[cat]) continue;
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

        getHealthPercentage() {
            let margin = this.marginNumeric;
            let pos = ((margin + 20) / 80) * 100;
            if (pos < 0) pos = 0;
            if (pos > 100) pos = 100;
            return pos;
        },

        getHealthColorClass() {
            let margin = this.marginNumeric;
            if (margin < 0) return 'text-red-500';
            if (margin < 20) return 'text-amber-500';
            return 'text-emerald-500';
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

        formatCleanProfitRange() {
            if (this.netProfit <= 0) return '0 WL - 0 WL';
            let min = (this.netProfit * 0.6).toFixed(1).replace(/\.0$/, '');
            let max = (this.netProfit * 0.8).toFixed(1).replace(/\.0$/, '');
            return `${min} WL - ${max} WL`;
        },

        resetTable() {
            this.prices = {};
            this.stocks = {};
        },

        resetCalculator() {
            try { localStorage.removeItem('gthelp-bbm-provider'); } catch(e) {}
            this.numPacks = 0;
            this.wlCostPerPack = 0;
            this.stocks = {};
            this.prices = {};
            for (let key in this.items) {
                this.items[key].type = '200/?';
            }
        }
    }));
});
