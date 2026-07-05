document.addEventListener('alpine:init', () => {
    Alpine.data('calculator', () => ({
        numPacks: 0,
        wlCostPerPack: 0,
        stocks: {
            caveColumnSeed: null,
            doorSeed: null,
            glassPaneSeed: null,
            grassSeed: null,
            lavaRockSeed: null,
            martianTreeSeed: null,
            signSeed: null,
            woodBlockSeed: null,
            amberGlassSeed: null,
            bricksSeed: null,
            crappySignSeed: null,
            daisySeed: null,
            happyJoyPlaqueSeed: null,
            mushroomSeed: null,
            rockBackgroundSeed: null,
            roseSeed: null,
            superCrateBoxSeed: null,
            terracottaPotSeed: null,
            torchSeed: null,
            wheatSeed: null,
            blackBlockSeed: null,
            brownBlockSeed: null,
            cliffsideSeed: null,
            greyBlockSeed: null,
            miniBlocksSeed: null,
            mudGlobSeed: null,
            pointySignSeed: null,
            redBlockSeed: null,
            woodenBackgroundSeed: null,
            woodenPlatformSeed: null,
            aquaBlockSeed: null,
            dangerSignSeed: null,
            evilBricksSeed: null,
            greenBlockSeed: null,
            rockPlatformSeed: null,
            stoneWallSeed: null,
            poppySeed: null,
            whiteBlockSeed: null,
            windowSeed: null,
            woodenTableSeed: null,
            yellowBlockSeed: null,
            barnBlockSeed: null,
            brickBackgroundSeed: null,
            cavePlatformSeed: null,
            floweryWallpaperSeed: null,
            glassBlockSeed: null,
            grimstoneSeed: null,
            redBricksSeed: null,
            toiletSeed: null,
            deathSpikesSeed: null,
            dungeonDoorSeed: null,
            pencilSeed: null,
            redWoodWallSeed: null,
            barnDoorSeed: null,
            bushSeed: null,
            devilHornsSeed: null,
            exclamationSignSeed: null,
            orangeBlockSeed: null,
            runeCarvedStonePillarSeed: null,
            sheetMusicBlankSeed: null,
            stalactiteSeed: null,
            stalagmiteSeed: null,
            texasLimestoneSeed: null,
            dwarvenColumnSeed: null,
            houseEntranceSeed: null,
            paintingDinkDuckSeed: null,
            paintingYerfdogSeed: null,
            stripeyWallpaperSeed: null,
            woodenWindowSeed: null,
            barrelSeed: null,
            checkerWallpaperSeed: null,
            darkBrownBlockSeed: null,
            darkGreyBlockSeed: null,
            darkRedBlockSeed: null,
            redGlassBlockSeed: null,
            sidewalkSeed: null,
            tenementBuildingSeed: null,
            tomatoSeed: null,
            windowCurtainsSeed: null,
            woodenChairSeed: null,
            blueberrySeed: null,
            darkAquaBlockSeed: null,
            darkGreenBlockSeed: null,
            romanticBushesSeed: null,
            staircaseSeed: null,
            waterBucketSeed: null,
            westernBuildingSeed: null,
            bathtubSeed: null,
            bigOldDownArrowSeed: null,
            bigOldSidewaysArrowSeed: null,
            darkYellowBlockSeed: null,
            sheetMusicDrumsSeed: null,
            tigerBlockSeed: null,
            twistedSpikesSeed: null,
            bedSeed: null,
            biohazardSignSeed: null,
            bubbleWrapSeed: null,
            dresserSeed: null,
            saloonDoorsSeed: null,
            sheetMusicPianoNoteSeed: null,
            vineyBlockSeed: null,
            vineyWallpaperSeed: null,
            westernBannerSeed: null,
            airDuctSeed: null,
            blueBlockSeed: null,
            latticeBackgroundSeed: null,
            oldeTimeyRadioSeed: null,
            orangeSeed: null,
            polkaDotBlockSeed: null,
            secretPassageSeed: null,
            sheetMusicFlatPianoSeed: null,
            sheetMusicSaxNoteSeed: null,
            streetSignSeed: null,
            tangramBlockSeed: null,
            darkOrangeBlockSeed: null,
            gargoyleSeed: null,
            portcullisSeed: null,
            sheetMusicFlatSaxSeed: null,
            ancientStoneGateSeed: null,
            arrowPlacardSeed: null,
            cactusSeed: null,
            picketFenceSeed: null,
            screenDoorSeed: null,
            blueStarWallpaperSeed: null,
            cuzcoWallMountSeed: null,
            dasRedBalloonSeed: null,
            foliageSeed: null,
            plumbingSeed: null,
            saltBlockSeed: null,
            sandstoneSeed: null,
            appleSeed: null,
            commandMoveSeed: null,
            forSaleSignSeed: null,
            iceCrustBlockSeed: null,
            purpleBlockSeed: null,
            sheetMusicFlatElectricGuitarSeed: null,
            sheetMusicFlatFluteSeed: null,
            sheetMusicFlatLyreSeed: null,
            sheetMusicFlatMexicanTrumpetSeed: null,
            sheetMusicFlatSpanishGuitarSeed: null,
            sheetMusicFlatViolinSeed: null,
            outieBlockSeed: null,
            rusticFenceSeed: null,
            schoolDeskSeed: null,
            airVentSeed: null,
            ancientBlockSeed: null,
            gemSignSeed: null,
            seaweedSeed: null,
            swissCheeseBlockSeed: null,
        },

        prices: {
            caveColumnSeed: null,
            doorSeed: null,
            glassPaneSeed: null,
            grassSeed: null,
            lavaRockSeed: null,
            martianTreeSeed: null,
            signSeed: null,
            woodBlockSeed: null,
            amberGlassSeed: null,
            bricksSeed: null,
            crappySignSeed: null,
            daisySeed: null,
            happyJoyPlaqueSeed: null,
            mushroomSeed: null,
            rockBackgroundSeed: null,
            roseSeed: null,
            superCrateBoxSeed: null,
            terracottaPotSeed: null,
            torchSeed: null,
            wheatSeed: null,
            blackBlockSeed: null,
            brownBlockSeed: null,
            cliffsideSeed: null,
            greyBlockSeed: null,
            miniBlocksSeed: null,
            mudGlobSeed: null,
            pointySignSeed: null,
            redBlockSeed: null,
            woodenBackgroundSeed: null,
            woodenPlatformSeed: null,
            aquaBlockSeed: null,
            dangerSignSeed: null,
            evilBricksSeed: null,
            greenBlockSeed: null,
            rockPlatformSeed: null,
            stoneWallSeed: null,
            poppySeed: null,
            whiteBlockSeed: null,
            windowSeed: null,
            woodenTableSeed: null,
            yellowBlockSeed: null,
            barnBlockSeed: null,
            brickBackgroundSeed: null,
            cavePlatformSeed: null,
            floweryWallpaperSeed: null,
            glassBlockSeed: null,
            grimstoneSeed: null,
            redBricksSeed: null,
            toiletSeed: null,
            deathSpikesSeed: null,
            dungeonDoorSeed: null,
            pencilSeed: null,
            redWoodWallSeed: null,
            barnDoorSeed: null,
            bushSeed: null,
            devilHornsSeed: null,
            exclamationSignSeed: null,
            orangeBlockSeed: null,
            runeCarvedStonePillarSeed: null,
            sheetMusicBlankSeed: null,
            stalactiteSeed: null,
            stalagmiteSeed: null,
            texasLimestoneSeed: null,
            dwarvenColumnSeed: null,
            houseEntranceSeed: null,
            paintingDinkDuckSeed: null,
            paintingYerfdogSeed: null,
            stripeyWallpaperSeed: null,
            woodenWindowSeed: null,
            barrelSeed: null,
            checkerWallpaperSeed: null,
            darkBrownBlockSeed: null,
            darkGreyBlockSeed: null,
            darkRedBlockSeed: null,
            redGlassBlockSeed: null,
            sidewalkSeed: null,
            tenementBuildingSeed: null,
            tomatoSeed: null,
            windowCurtainsSeed: null,
            woodenChairSeed: null,
            blueberrySeed: null,
            darkAquaBlockSeed: null,
            darkGreenBlockSeed: null,
            romanticBushesSeed: null,
            staircaseSeed: null,
            waterBucketSeed: null,
            westernBuildingSeed: null,
            bathtubSeed: null,
            bigOldDownArrowSeed: null,
            bigOldSidewaysArrowSeed: null,
            darkYellowBlockSeed: null,
            sheetMusicDrumsSeed: null,
            tigerBlockSeed: null,
            twistedSpikesSeed: null,
            bedSeed: null,
            biohazardSignSeed: null,
            bubbleWrapSeed: null,
            dresserSeed: null,
            saloonDoorsSeed: null,
            sheetMusicPianoNoteSeed: null,
            vineyBlockSeed: null,
            vineyWallpaperSeed: null,
            westernBannerSeed: null,
            airDuctSeed: null,
            blueBlockSeed: null,
            latticeBackgroundSeed: null,
            oldeTimeyRadioSeed: null,
            orangeSeed: null,
            polkaDotBlockSeed: null,
            secretPassageSeed: null,
            sheetMusicFlatPianoSeed: null,
            sheetMusicSaxNoteSeed: null,
            streetSignSeed: null,
            tangramBlockSeed: null,
            darkOrangeBlockSeed: null,
            gargoyleSeed: null,
            portcullisSeed: null,
            sheetMusicFlatSaxSeed: null,
            ancientStoneGateSeed: null,
            arrowPlacardSeed: null,
            cactusSeed: null,
            picketFenceSeed: null,
            screenDoorSeed: null,
            blueStarWallpaperSeed: null,
            cuzcoWallMountSeed: null,
            dasRedBalloonSeed: null,
            foliageSeed: null,
            plumbingSeed: null,
            saltBlockSeed: null,
            sandstoneSeed: null,
            appleSeed: null,
            commandMoveSeed: null,
            forSaleSignSeed: null,
            iceCrustBlockSeed: null,
            purpleBlockSeed: null,
            sheetMusicFlatElectricGuitarSeed: null,
            sheetMusicFlatFluteSeed: null,
            sheetMusicFlatLyreSeed: null,
            sheetMusicFlatMexicanTrumpetSeed: null,
            sheetMusicFlatSpanishGuitarSeed: null,
            sheetMusicFlatViolinSeed: null,
            outieBlockSeed: null,
            rusticFenceSeed: null,
            schoolDeskSeed: null,
            airVentSeed: null,
            ancientBlockSeed: null,
            gemSignSeed: null,
            seaweedSeed: null,
            swissCheeseBlockSeed: null,
        },

        items: {
            caveColumnSeed: { name: 'Cave Column Seed', type: 'per wl', icon: '/images/ssp/seeds/image_17.png' },
            doorSeed: { name: 'Door Seed', type: 'per wl', icon: '/images/ssp/seeds/image_18.png' },
            glassPaneSeed: { name: 'Glass Pane Seed', type: 'per wl', icon: '/images/ssp/seeds/image_19.png' },
            grassSeed: { name: 'Grass Seed', type: 'per wl', icon: '/images/ssp/seeds/image_20.png' },
            lavaRockSeed: { name: 'Lava Rock Seed', type: 'per wl', icon: '/images/ssp/seeds/image_21.png' },
            martianTreeSeed: { name: 'Martian Tree Seed', type: 'per wl', icon: '/images/ssp/seeds/image_22.png' },
            signSeed: { name: 'Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_23.png' },
            woodBlockSeed: { name: 'Wood Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_24.png' },
            amberGlassSeed: { name: 'Amber Glass Seed', type: 'per wl', icon: '/images/ssp/seeds/image_25.png' },
            bricksSeed: { name: 'Bricks Seed', type: 'per wl', icon: '/images/ssp/seeds/image_26.png' },
            crappySignSeed: { name: 'Crappy Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_27.png' },
            daisySeed: { name: 'Daisy Seed', type: 'per wl', icon: '/images/ssp/seeds/image_28.png' },
            happyJoyPlaqueSeed: { name: 'Happy Joy Plaque Seed', type: 'per wl', icon: '/images/ssp/seeds/image_29.png' },
            mushroomSeed: { name: 'Mushroom Seed', type: 'per wl', icon: '/images/ssp/seeds/image_30.png' },
            rockBackgroundSeed: { name: 'Rock Background Seed', type: 'per wl', icon: '/images/ssp/seeds/image_31.png' },
            roseSeed: { name: 'Rose Seed', type: 'per wl', icon: '/images/ssp/seeds/image_32.png' },
            superCrateBoxSeed: { name: 'Super Crate Box Seed', type: 'per wl', icon: '/images/ssp/seeds/image_33.png' },
            terracottaPotSeed: { name: 'Terracotta Pot Seed', type: 'per wl', icon: '/images/ssp/seeds/image_34.png' },
            torchSeed: { name: 'Torch Seed', type: 'per wl', icon: '/images/ssp/seeds/image_35.png' },
            wheatSeed: { name: 'Wheat Seed', type: 'per wl', icon: '/images/ssp/seeds/image_36.png' },
            blackBlockSeed: { name: 'Black Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_37.png' },
            brownBlockSeed: { name: 'Brown Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_38.png' },
            cliffsideSeed: { name: 'Cliffside Seed', type: 'per wl', icon: '/images/ssp/seeds/image_39.png' },
            greyBlockSeed: { name: 'Grey Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_40.png' },
            miniBlocksSeed: { name: 'Mini Blocks Seed', type: 'per wl', icon: '/images/ssp/seeds/image_41.png' },
            mudGlobSeed: { name: 'Mud Glob Seed', type: 'per wl', icon: '/images/ssp/seeds/image_42.png' },
            pointySignSeed: { name: 'Pointy Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_43.png' },
            redBlockSeed: { name: 'Red Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_44.png' },
            woodenBackgroundSeed: { name: 'Wooden Background Seed', type: 'per wl', icon: '/images/ssp/seeds/image_45.png' },
            woodenPlatformSeed: { name: 'Wooden Platform Seed', type: 'per wl', icon: '/images/ssp/seeds/image_46.png' },
            aquaBlockSeed: { name: 'Aqua Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_47.png' },
            dangerSignSeed: { name: 'Danger Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_48.png' },
            evilBricksSeed: { name: 'Evil Bricks Seed', type: 'per wl', icon: '/images/ssp/seeds/image_49.png' },
            greenBlockSeed: { name: 'Green Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_50.png' },
            rockPlatformSeed: { name: 'Rock Platform Seed', type: 'per wl', icon: '/images/ssp/seeds/image_51.png' },
            stoneWallSeed: { name: 'Stone Wall Seed', type: 'per wl', icon: '/images/ssp/seeds/image_52.png' },
            poppySeed: { name: 'Poppy Seed', type: 'per wl', icon: '/images/ssp/seeds/image_53.png' },
            whiteBlockSeed: { name: 'White Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_54.png' },
            windowSeed: { name: 'Window Seed', type: 'per wl', icon: '/images/ssp/seeds/image_55.png' },
            woodenTableSeed: { name: 'Wooden Table Seed', type: 'per wl', icon: '/images/ssp/seeds/image_56.png' },
            yellowBlockSeed: { name: 'Yellow Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_57.png' },
            barnBlockSeed: { name: 'Barn Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_58.png' },
            brickBackgroundSeed: { name: 'Brick Background Seed', type: 'per wl', icon: '/images/ssp/seeds/image_59.png' },
            cavePlatformSeed: { name: 'Cave Platform Seed', type: 'per wl', icon: '/images/ssp/seeds/image_60.png' },
            floweryWallpaperSeed: { name: 'Flowery Wallpaper Seed', type: 'per wl', icon: '/images/ssp/seeds/image_61.png' },
            glassBlockSeed: { name: 'Glass Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_62.png' },
            grimstoneSeed: { name: 'Grimstone Seed', type: 'per wl', icon: '/images/ssp/seeds/image_63.png' },
            redBricksSeed: { name: 'Red Bricks Seed', type: 'per wl', icon: '/images/ssp/seeds/image_64.png' },
            toiletSeed: { name: 'Toilet Seed', type: 'per wl', icon: '/images/ssp/seeds/image_65.png' },
            deathSpikesSeed: { name: 'Death Spikes Seed', type: 'per wl', icon: '/images/ssp/seeds/image_66.png' },
            dungeonDoorSeed: { name: 'Dungeon Door Seed', type: 'per wl', icon: '/images/ssp/seeds/image_67.png' },
            pencilSeed: { name: 'Pencil Seed', type: 'per wl', icon: '/images/ssp/seeds/image_68.png' },
            redWoodWallSeed: { name: 'Red Wood Wall Seed', type: 'per wl', icon: '/images/ssp/seeds/image_69.png' },
            barnDoorSeed: { name: 'Barn Door Seed', type: 'per wl', icon: '/images/ssp/seeds/image_70.png' },
            bushSeed: { name: 'Bush Seed', type: 'per wl', icon: '/images/ssp/seeds/image_71.png' },
            devilHornsSeed: { name: 'Devil Horns Seed', type: 'per wl', icon: '/images/ssp/seeds/image_72.png' },
            exclamationSignSeed: { name: 'Exclamation Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_73.png' },
            orangeBlockSeed: { name: 'Orange Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_74.png' },
            runeCarvedStonePillarSeed: { name: 'Rune Carved Stone Pillar Seed', type: 'per wl', icon: '/images/ssp/seeds/image_75.png' },
            sheetMusicBlankSeed: { name: 'Sheet Music: Blank Seed', type: 'per wl', icon: '/images/ssp/seeds/image_76.png' },
            stalactiteSeed: { name: 'Stalactite Seed', type: 'per wl', icon: '/images/ssp/seeds/image_77.png' },
            stalagmiteSeed: { name: 'Stalagmite Seed', type: 'per wl', icon: '/images/ssp/seeds/image_78.png' },
            texasLimestoneSeed: { name: 'Texas Limestone Seed', type: 'per wl', icon: '/images/ssp/seeds/image_79.png' },
            dwarvenColumnSeed: { name: 'Dwarven Column Seed', type: 'per wl', icon: '/images/ssp/seeds/image_80.png' },
            houseEntranceSeed: { name: 'House Entrance Seed', type: 'per wl', icon: '/images/ssp/seeds/image_81.png' },
            paintingDinkDuckSeed: { name: 'Painting: Dink Duck Seed', type: 'per wl', icon: '/images/ssp/seeds/image_82.png' },
            paintingYerfdogSeed: { name: 'Painting: Yerfdog Seed', type: 'per wl', icon: '/images/ssp/seeds/image_83.png' },
            stripeyWallpaperSeed: { name: 'Stripey Wallpaper Seed', type: 'per wl', icon: '/images/ssp/seeds/image_84.png' },
            woodenWindowSeed: { name: 'Wooden Window Seed', type: 'per wl', icon: '/images/ssp/seeds/image_85.png' },
            barrelSeed: { name: 'Barrel Seed', type: 'per wl', icon: '/images/ssp/seeds/image_86.png' },
            checkerWallpaperSeed: { name: 'Checker Wallpaper Seed', type: 'per wl', icon: '/images/ssp/seeds/image_87.png' },
            darkBrownBlockSeed: { name: 'Dark Brown Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_88.png' },
            darkGreyBlockSeed: { name: 'Dark Grey Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_89.png' },
            darkRedBlockSeed: { name: 'Dark Red Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_90.png' },
            redGlassBlockSeed: { name: 'Red Glass Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_91.png' },
            sidewalkSeed: { name: 'Sidewalk Seed', type: 'per wl', icon: '/images/ssp/seeds/image_92.png' },
            tenementBuildingSeed: { name: 'Tenement Building Seed', type: 'per wl', icon: '/images/ssp/seeds/image_93.png' },
            tomatoSeed: { name: 'Tomato Seed', type: 'per wl', icon: '/images/ssp/seeds/image_94.png' },
            windowCurtainsSeed: { name: 'Window Curtains Seed', type: 'per wl', icon: '/images/ssp/seeds/image_95.png' },
            woodenChairSeed: { name: 'Wooden Chair Seed', type: 'per wl', icon: '/images/ssp/seeds/image_96.png' },
            blueberrySeed: { name: 'Blueberry Seed', type: 'per wl', icon: '/images/ssp/seeds/image_97.png' },
            darkAquaBlockSeed: { name: 'Dark Aqua Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_98.png' },
            darkGreenBlockSeed: { name: 'Dark Green Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_99.png' },
            romanticBushesSeed: { name: 'Romantic Bushes Seed', type: 'per wl', icon: '/images/ssp/seeds/image_100.png' },
            staircaseSeed: { name: 'Staircase Seed', type: 'per wl', icon: '/images/ssp/seeds/image_101.png' },
            waterBucketSeed: { name: 'Water Bucket Seed', type: 'per wl', icon: '/images/ssp/seeds/image_102.png' },
            westernBuildingSeed: { name: 'Western Building Seed', type: 'per wl', icon: '/images/ssp/seeds/image_103.png' },
            bathtubSeed: { name: 'Bathtub Seed', type: 'per wl', icon: '/images/ssp/seeds/image_104.png' },
            bigOldDownArrowSeed: { name: 'Big Old Down Arrow Seed', type: 'per wl', icon: '/images/ssp/seeds/image_105.png' },
            bigOldSidewaysArrowSeed: { name: 'Big Old Sideways Arrow Seed', type: 'per wl', icon: '/images/ssp/seeds/image_106.png' },
            darkYellowBlockSeed: { name: 'Dark Yellow Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_107.png' },
            sheetMusicDrumsSeed: { name: 'Sheet Music: Drums Seed', type: 'per wl', icon: '/images/ssp/seeds/image_108.png' },
            tigerBlockSeed: { name: 'Tiger Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_109.png' },
            twistedSpikesSeed: { name: 'Twisted Spikes Seed', type: 'per wl', icon: '/images/ssp/seeds/image_110.png' },
            bedSeed: { name: 'Bed Seed', type: 'per wl', icon: '/images/ssp/seeds/image_111.png' },
            biohazardSignSeed: { name: 'Biohazard Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_112.png' },
            bubbleWrapSeed: { name: 'Bubble Wrap Seed', type: 'per wl', icon: '/images/ssp/seeds/image_113.png' },
            dresserSeed: { name: 'Dresser Seed', type: 'per wl', icon: '/images/ssp/seeds/image_114.png' },
            saloonDoorsSeed: { name: 'Saloon Doors Seed', type: 'per wl', icon: '/images/ssp/seeds/image_115.png' },
            sheetMusicPianoNoteSeed: { name: 'Sheet Music: Piano Note Seed', type: 'per wl', icon: '/images/ssp/seeds/image_116.png' },
            vineyBlockSeed: { name: 'Viney Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_117.png' },
            vineyWallpaperSeed: { name: 'Viney Wallpaper Seed', type: 'per wl', icon: '/images/ssp/seeds/image_118.png' },
            westernBannerSeed: { name: 'Western Banner Seed', type: 'per wl', icon: '/images/ssp/seeds/image_119.png' },
            airDuctSeed: { name: 'Air Duct Seed', type: 'per wl', icon: '/images/ssp/seeds/image_120.png' },
            blueBlockSeed: { name: 'Blue Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_121.png' },
            latticeBackgroundSeed: { name: 'Lattice Background Seed', type: 'per wl', icon: '/images/ssp/seeds/image_122.png' },
            oldeTimeyRadioSeed: { name: 'Olde Timey Radio Seed', type: 'per wl', icon: '/images/ssp/seeds/image_123.png' },
            orangeSeed: { name: 'Orange Seed', type: 'per wl', icon: '/images/ssp/seeds/image_124.png' },
            polkaDotBlockSeed: { name: 'Polka Dot Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_125.png' },
            secretPassageSeed: { name: 'Secret Passage Seed', type: 'per wl', icon: '/images/ssp/seeds/image_126.png' },
            sheetMusicFlatPianoSeed: { name: 'Sheet Music: Flat Piano Seed', type: 'per wl', icon: '/images/ssp/seeds/image_127.png' },
            sheetMusicSaxNoteSeed: { name: 'Sheet Music: Sax Note Seed', type: 'per wl', icon: '/images/ssp/seeds/image_128.png' },
            streetSignSeed: { name: 'Street Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_129.png' },
            tangramBlockSeed: { name: 'Tangram Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_130.png' },
            darkOrangeBlockSeed: { name: 'Dark Orange Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_131.png' },
            gargoyleSeed: { name: 'Gargoyle Seed', type: 'per wl', icon: '/images/ssp/seeds/image_132.png' },
            portcullisSeed: { name: 'Portcullis Seed', type: 'per wl', icon: '/images/ssp/seeds/image_133.png' },
            sheetMusicFlatSaxSeed: { name: 'Sheet Music: Flat Sax Seed', type: 'per wl', icon: '/images/ssp/seeds/image_134.png' },
            ancientStoneGateSeed: { name: 'Ancient Stone Gate Seed', type: 'per wl', icon: '/images/ssp/seeds/image_135.png' },
            arrowPlacardSeed: { name: 'Arrow Placard Seed', type: 'per wl', icon: '/images/ssp/seeds/image_136.png' },
            cactusSeed: { name: 'Cactus Seed', type: 'per wl', icon: '/images/ssp/seeds/image_137.png' },
            picketFenceSeed: { name: 'Picket Fence Seed', type: 'per wl', icon: '/images/ssp/seeds/image_138.png' },
            screenDoorSeed: { name: 'Screen Door Seed', type: 'per wl', icon: '/images/ssp/seeds/image_139.png' },
            blueStarWallpaperSeed: { name: 'Blue Star Wallpaper Seed', type: 'per wl', icon: '/images/ssp/seeds/image_140.png' },
            cuzcoWallMountSeed: { name: 'Cuzco Wall Mount Seed', type: 'per wl', icon: '/images/ssp/seeds/image_141.png' },
            dasRedBalloonSeed: { name: 'Das Red Balloon Seed', type: 'per wl', icon: '/images/ssp/seeds/image_142.png' },
            foliageSeed: { name: 'Foliage Seed', type: 'per wl', icon: '/images/ssp/seeds/image_143.png' },
            plumbingSeed: { name: 'Plumbing Seed', type: 'per wl', icon: '/images/ssp/seeds/image_144.png' },
            saltBlockSeed: { name: 'Salt Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_145.png' },
            sandstoneSeed: { name: 'Sandstone Seed', type: 'per wl', icon: '/images/ssp/seeds/image_146.png' },
            appleSeed: { name: 'Apple Seed', type: 'per wl', icon: '/images/ssp/seeds/image_147.png' },
            commandMoveSeed: { name: 'Command - Move Seed', type: 'per wl', icon: '/images/ssp/seeds/image_148.png' },
            forSaleSignSeed: { name: 'For Sale Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_149.png' },
            iceCrustBlockSeed: { name: 'Ice Crust Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_150.png' },
            purpleBlockSeed: { name: 'Purple Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_151.png' },
            sheetMusicFlatElectricGuitarSeed: { name: 'Sheet Music: Flat Electric Guitar Seed', type: 'per wl', icon: '/images/ssp/seeds/image_152.png' },
            sheetMusicFlatFluteSeed: { name: 'Sheet Music: Flat Flute Seed', type: 'per wl', icon: '/images/ssp/seeds/image_153.png' },
            sheetMusicFlatLyreSeed: { name: 'Sheet Music: Flat Lyre Seed', type: 'per wl', icon: '/images/ssp/seeds/image_154.png' },
            sheetMusicFlatMexicanTrumpetSeed: { name: 'Sheet Music: Flat Mexican Trumpet Seed', type: 'per wl', icon: '/images/ssp/seeds/image_155.png' },
            sheetMusicFlatSpanishGuitarSeed: { name: 'Sheet Music: Flat Spanish Guitar Seed', type: 'per wl', icon: '/images/ssp/seeds/image_156.png' },
            sheetMusicFlatViolinSeed: { name: 'Sheet Music: Flat Violin Seed', type: 'per wl', icon: '/images/ssp/seeds/image_157.png' },
            outieBlockSeed: { name: 'Outie Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_158.png' },
            rusticFenceSeed: { name: 'Rustic Fence Seed', type: 'per wl', icon: '/images/ssp/seeds/image_159.png' },
            schoolDeskSeed: { name: 'School Desk Seed', type: 'per wl', icon: '/images/ssp/seeds/image_160.png' },
            airVentSeed: { name: 'Air Vent Seed', type: 'per wl', icon: '/images/ssp/seeds/image_161.png' },
            ancientBlockSeed: { name: 'Ancient Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_162.png' },
            gemSignSeed: { name: 'Gem Sign Seed', type: 'per wl', icon: '/images/ssp/seeds/image_163.png' },
            seaweedSeed: { name: 'Seaweed Seed', type: 'per wl', icon: '/images/ssp/seeds/image_164.png' },
            swissCheeseBlockSeed: { name: 'Swiss Cheese Block Seed', type: 'per wl', icon: '/images/ssp/seeds/image_165.png' },
        },

        get totalCost() {
            return ((this.numPacks || 0) / 200) * (this.wlCostPerPack || 0);
        },

        init() {
            const key = 'gthelp-ssp-pack';
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
            try { localStorage.removeItem('gthelp-ssp-pack'); } catch(e) {}
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

