<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishKinds = DishKind::all()->keyBy(fn (DishKind $dishKind) => $dishKind->getTranslation('name', 'nl', false));

        $dishesByKind = [
            'Soep' => [
                ['1', 'Soep Ling Fa', 'Soup Ling Fa', '', '', '3.80'],
                ['2', 'Kippensoep', 'Chicken soup', '', '', '2.90'],
                ['3', 'Tomatensoep', 'Tomato soup', '', '', '2.90'],
                ['4', 'Haaienvinnensoep', 'Shark fin soup', '', '', '3.10'],
                ['5', 'Champignonsoep', 'Mushroom soup', '', '', '3.30'],
                ['6', 'Pekingsoep', 'Peking soup', '', '', '3.80'],
                ['7', 'Wan Tan Soep', 'Wonton soup', '', '', '4.30'],
                ['8', 'Chinese Champignonsoep', 'Chinese mushroom soup', '', '', '4.10'],
            ],
            'Voorgerecht' => [
                ['10', 'Loempia Ling Fa', 'Spring roll Ling Fa', 'met atjar, ananas en pindasaus', 'with pickled vegetables, pineapple and peanut sauce', '6.20'],
                ['11', 'Loempia Compleet', 'Spring roll complete', 'met gesmoord rundvlees en pikante saus', 'with braised beef and spicy sauce', '6.20'],
                ['12', 'Loempia met Kip', 'Spring roll with chicken', '', '', '3.90'],
                ['13', 'Loempia', 'Spring roll', '', '', '3.80'],
                ['14', 'Chinese mini loempia', 'Chinese mini spring rolls', '4 st.', '4 pcs', '4.90'],
                ['14A', 'Vegetarische mini loempia', 'Vegetarian mini spring rolls', '12 st.', '12 pcs', '4.90'],
                ['15', 'Kroepoek', 'Prawn crackers', '', '', '2.50'],
                ['15A', 'Casave Kroepoek', 'Cassava crackers', '', '', '2.70'],
                ['16', 'Pangsit Goreng', 'Fried wontons', '7 st.', '7 pcs', '3.90'],
                ['17', 'Pisang Goreng', 'Fried banana', '5 st.', '5 pcs', '3.40'],
                ['18', 'Chinese Dim Sum', 'Chinese dim sum', 'mini loempia, kerry ko, pangsit goreng, garnalenpasteitje', 'mini spring roll, kerry ko, fried wonton, prawn pastry', '5.40'],
                ['19', 'Saté Babi', 'Pork satay', '4 st.', '4 pcs', '5.40'],
                ['20', 'Saté Ajam', 'Chicken satay', '4 st.', '4 pcs', '5.40'],
                ['20A', 'Saté Garnalen', 'Prawn satay', '3 st.', '3 pcs', '9.90'],
                ['21', 'Fong Mei Ha', 'Fong mei ha', 'krokant gepaneerd garnalen, 4 st.', 'crispy breaded prawns, 4 pcs', '8.10'],
                ['22', 'Patat', 'Fries', '', '', '2.30'],
                ['23', 'Tsa Siu Mai', 'Tsa siu mai', 'gebakken vleespasteitje, 4 st.', 'fried meat pastry, 4 pcs', '3.50'],
                ['24', 'Atjar', 'Pickled vegetables', '', '', '3.00'],
                ['25', 'Witte Rijst', 'White rice', '', '', '3.00'],
                ['26', 'Grote pindasaus', 'Large peanut sauce', '', '', '3.90'],
                ['27', 'Kleine pindasaus', 'Small peanut sauce', '', '', '2.30'],
                ['28', 'Kippenpootje', 'Chicken drumstick', '', '', '2.30'],
                ['29', 'Halve kip', 'Half chicken', '', '', '6.00'],
                ['29H', 'Kroket', 'Croquette', '', '', '1.40'],
                ['29G', 'Frikandel', 'Frikandel', '', '', '1.40'],
                ['180H', 'Kleine Sambal', 'Small sambal', '', '', '2.50'],
            ],
            'Bami & Nasi gerechten' => [
                ['30', 'Bami of Nasi Goreng Ling Fa', 'Fried noodles or rice Ling Fa', 'Foe Yong Hai, Babi Pangang, saté en kippenpootje', 'Foe yong hai, babi pangang, satay and chicken drumstick', '14.30'],
                ['31', 'Bami of Nasi Goreng met ei', 'Fried noodles or rice with egg', '', '', '5.00'],
                ['32', 'Bami of Nasi Goreng speciaal', 'Special fried noodles or rice', '', '', '8.50'],
                ['33', 'Bami of Nasi Goreng met saté', 'Fried noodles or rice with satay', '3 st.', '3 pcs', '8.50'],
                ['34', 'Nasi Yeung Chow', 'Nasi yeung chow', '', '', '10.90'],
                ['34A', 'Nasi Yeung Chow', 'Nasi yeung chow', 'met garnaaltjes en Cha Sieuw-vlees', 'with small prawns and cha sieuw pork', '13.00'],
                ['35', 'Bami of Nasi Malay', 'Fried noodles or rice Malay style', '', '', '9.30'],
                ['36', 'Bami of Nasi met kipfilet', 'Fried noodles or rice with chicken fillet', '', '', '9.30'],
                ['37', 'Bami of Nasi met varkensvlees', 'Fried noodles or rice with pork', '', '', '9.30'],
                ['38', 'Bami of Nasi met garnalen', 'Fried noodles or rice with prawns', '', '', '14.30'],
                ['39', 'Bami of Nasi met ossenhaas', 'Fried noodles or rice with beef tenderloin', '', '', '15.30'],
            ],
            'Combinatie gerechten (met witte rijst)' => [
                ['40', 'Babi Pangang, Foe Yong Hai en saté', 'Babi pangang, foe yong hai and satay', '', '', '15.80'],
                ['41', 'Babi Pangang, Tjap Tjoy en saté', 'Babi pangang, chop suey and satay', '', '', '15.80'],
                ['42', 'Babi Pangang, Koe Loe Yuk en saté', 'Babi pangang, sweet and sour pork and satay', '', '', '15.80'],
                ['43', 'Babi Pangang, Tau Sie Kai en saté', 'Babi pangang, tau sie kai and satay', '', '', '16.50'],
                ['44', 'Koe Loe Yuk, Foe Yong Hai en saté', 'Sweet and sour pork, foe yong hai and satay', '', '', '15.80'],
                ['45', 'Koe Loe Yuk, Tjap Tjoy en saté', 'Sweet and sour pork, chop suey and satay', '', '', '15.80'],
                ['46', 'Foe Yong Hai, Tjap Tjoy en saté', 'Foe yong hai, chop suey and satay', '', '', '15.80'],
                ['47', 'Foe Yong Hai, Kip Kerrie en Saté', 'Foe yong hai, chicken curry and satay', '', '', '16.50'],
            ],
            'Mihoen gerechten' => [
                ['50', 'Mihoen Ling Fa', 'Rice noodles Ling Fa', 'Foe Yong Hai, Babi Pangang, saté en kippenpootje', 'Foe yong hai, babi pangang, satay and chicken drumstick', '16.40'],
                ['51', 'Mihoen met varkensvlees', 'Rice noodles with pork', '', '', '9.30'],
                ['52', 'Mihoen met kipfilet', 'Rice noodles with chicken fillet', '', '', '10.40'],
                ['53', 'Mihoen met ossenhaas', 'Rice noodles with beef tenderloin', '', '', '16.40'],
                ['54', 'Mihoen met garnalen', 'Rice noodles with prawns', '', '', '15.30'],
                ['55', 'Mihoen Singapore-style', 'Rice noodles Singapore style', 'met kleine garnaaltjes en Cha Sieuw-vlees en kerrie poeder', 'with small prawns, cha sieuw pork and curry powder', '11.90'],
                ['56', 'Mihoen met Cha Sieuw vlees', 'Rice noodles with cha sieuw pork', '', '', '11.20'],
            ],
            'Chinese bami gerechten' => [
                ['57', 'Chinese Bami Ling Fa', 'Chinese noodles Ling Fa', 'Foe Yong Hai, Babi Pangang, saté en kippenpootje', 'Foe yong hai, babi pangang, satay and chicken drumstick', '16.90'],
                ['58', 'Chinese Bami met varkensvlees', 'Chinese noodles with pork', '', '', '10.10'],
                ['58A', 'Chinese Bami met kipfilet', 'Chinese noodles with chicken fillet', '', '', '11.20'],
                ['58B', 'Chinese Bami met Cha Sieuw-vlees', 'Chinese noodles with cha sieuw pork', '', '', '12.20'],
                ['58C', 'Chinese Bami met garnalen', 'Chinese noodles with prawns', '', '', '15.80'],
                ['58D', 'Chinese Bami met ossenhaas', 'Chinese noodles with beef tenderloin', '', '', '17.40'],
            ],
            'Indische gerechten' => [
                ['M1', 'Bami of Nasi Rames Ling Fa', 'Fried noodles or rice rames Ling Fa', 'Babi Pangang, Foe Yong Hai, Daging Roedjak, Atjar en kippenpootje', 'Babi pangang, foe yong hai, daging roedjak, pickled vegetables and chicken drumstick', '15.30'],
                ['M2', 'Bami of Nasi Rames', 'Fried noodles or rice rames', '', '', '8.80'],
                ['M3', 'Bami of Nasi Rames speciaal', 'Special fried noodles or rice rames', '', '', '10.80'],
                ['M4', 'Gado Gado', 'Gado gado', 'met witte rijst', 'with white rice', '7.60'],
                ['M5', 'Daging Smoor', 'Daging smoor', 'met witte rijst', 'with white rice', '13.30'],
                ['M6', 'Daging Roedjak', 'Daging roedjak', 'met witte rijst', 'with white rice', '13.30'],
            ],
            'Eiergerechten' => [
                ['59', 'Foe Yong Hai Ling Fa', 'Foe yong hai Ling Fa', 'ossenhaas, garnalen en kipfilet', 'beef tenderloin, prawns and chicken fillet', '16.40'],
                ['60', 'Foe Yong Hai met varkensvlees', 'Foe yong hai with pork', '', '', '8.80'],
                ['61', 'Foe Yong Hai met kipfilet', 'Foe yong hai with chicken fillet', '', '', '9.20'],
                ['62', 'Foe Yong Hai met garnalen', 'Foe yong hai with prawns', '', '', '15.30'],
                ['63', 'Foe Yong Hai met krab', 'Foe yong hai with crab', '', '', '15.30'],
                ['63A', 'Foe Yong Hai met Cha Sieuw Vlees', 'Foe yong hai with cha sieuw pork', '', '', '11.20'],
                ['63B', 'Foe Yong Hai met ossenhaas', 'Foe yong hai with beef tenderloin', '', '', '16.40'],
            ],
            'Groenten gerechten' => [
                ['64', 'Tjap Tjoy Ling Fa', 'Chop suey Ling Fa', 'ossenhaas, garnalen en kipfilet', 'beef tenderloin, prawns and chicken fillet', '16.40'],
                ['65', 'Tjap Tjoy met varkensvlees', 'Chop suey with pork', '', '', '8.80'],
                ['66', 'Tjap Tjoy met kipfilet', 'Chop suey with chicken fillet', '', '', '9.20'],
                ['67', 'Tjap Tjoy met ossenhaas', 'Chop suey with beef tenderloin', '', '', '16.40'],
                ['68', 'Tjap Tjoy met garnalen', 'Chop suey with prawns', '', '', '15.30'],
            ],
            'Vleesgerechten (met witte rijst)' => [
                ['70', 'Babi Pangang', 'Babi pangang', '', '', '12.20'],
                ['71', 'Babi Pangang in ketjapsaus', 'Babi pangang in soy sauce', '', '', '12.30'],
                ['72', 'Cha Sieuw', 'Cha sieuw', 'rood geroosterd varkensvlees', 'red roasted pork', '13.30'],
                ['73', 'Cha Sieuw in pikante saus', 'Cha sieuw in spicy sauce', '', '', '13.80'],
                ['74', 'Geroosterde speenvarken', 'Roasted suckling pig', '', '', '13.80'],
                ['75', 'Koe Loe Yuk', 'Sweet and sour pork', 'bolletjes vlees met zoetzure saus', 'meatballs with sweet and sour sauce', '11.90'],
                ['76', 'Varkenshaas met kerriesaus', 'Pork tenderloin with curry sauce', '', '', '11.90'],
                ['77', 'Varkenshaas met ketjapsaus', 'Pork tenderloin with soy sauce', '', '', '11.90'],
                ['78', 'Varkenshaas met tomatensaus', 'Pork tenderloin with tomato sauce', '', '', '11.90'],
                ['78A', 'Varkenshaas met champignons in knoflooksaus', 'Pork tenderloin with mushrooms in garlic sauce', '', '', '11.90'],
                ['78B', 'Varkenshaas met Chinese champignons', 'Pork tenderloin with Chinese mushrooms', '', '', '12.20'],
                ['79', 'Varkenshaas met zwarte bonensaus', 'Pork tenderloin with black bean sauce', '', '', '12.20'],
                ['79A', 'Varkenshaas met verse ananas in zoetzure saus', 'Pork tenderloin with fresh pineapple in sweet and sour sauce', '', '', '13.30'],
                ['79B', 'Yu Sian Yuk', 'Yu sian yuk', 'varkenshaas met licht zoet pikante kruidensaus', 'pork tenderloin in a mildly sweet and spicy herb sauce', '13.30'],
                ['79C', 'Sze Chuan Yuk', 'Sze chuan yuk', 'varkenshaas met pittige kruidensaus', 'pork tenderloin in a spicy herb sauce', '13.30'],
            ],
            'Kipgerechten (met witte rijst)' => [
                ['80', 'Ajam Pangang', 'Ajam pangang', '', '', '13.00'],
                ['81', 'Ajam Pangang in ketjapsaus', 'Ajam pangang in soy sauce', '', '', '13.00'],
                ['82', 'Koe Loe Kai', 'Sweet and sour chicken', 'bolletjes kip met zoetzure saus', 'chicken balls with sweet and sour sauce', '13.00'],
                ['83', 'Kipfilet met kerriesaus', 'Chicken fillet with curry sauce', '', '', '13.00'],
                ['84', 'Kipfilet met champignons in knoflooksaus', 'Chicken fillet with mushrooms in garlic sauce', '', '', '13.00'],
                ['85', 'Kipfilet met tomatensaus', 'Chicken fillet with tomato sauce', '', '', '13.00'],
                ['86', 'Kipfilet met ketjapsaus', 'Chicken fillet with soy sauce', '', '', '13.00'],
                ['87', 'Kipfilet met broccoli in knoflooksaus', 'Chicken fillet with broccoli in garlic sauce', '', '', '13.30'],
                ['88', 'Kipfilet met Chinese champignons', 'Chicken fillet with Chinese mushrooms', '', '', '13.30'],
                ['89', 'Kipfilet met zwarte pepersaus', 'Chicken fillet with black pepper sauce', '', '', '13.30'],
                ['90', 'Kipfilet met verse ananas in zoetzure saus', 'Chicken fillet with fresh pineapple in sweet and sour sauce', '', '', '13.30'],
                ['91', 'Kipfilet met zwarte pepersaus', 'Chicken fillet with black pepper sauce', '', '', '13.30'],
                ['92', 'Tjieuw Yem Kai', 'Tjieuw yem kai', 'licht gebraden kipfilet met zout en peper', 'lightly fried chicken fillet with salt and pepper', '13.30'],
                ['93', 'Yao Koe Kai', 'Yao koe kai', 'kipfilet met cashewnoten in licht pikante saus', 'chicken fillet with cashew nuts in a mildly spicy sauce', '13.30'],
                ['94', 'Lychee Kai', 'Lychee kai', 'licht gebraden kipfilet met lychee in zoetzure saus', 'lightly fried chicken fillet with lychee in sweet and sour sauce', '13.80'],
                ['95', 'Yu Sian Kai', 'Yu sian kai', 'kipfilet met licht zoet pikante kruidensaus', 'chicken fillet in a mildly sweet and spicy herb sauce', '13.30'],
                ['96', 'Sze Chuan Kai', 'Sze chuan kai', 'kipfilet met pittige kruidensaus', 'chicken fillet in a spicy herb sauce', '13.80'],
                ['97', 'Kung Bao Kai', 'Kung bao kai', 'kipfilet met cashewnoten in pittige saus', 'chicken fillet with cashew nuts in a spicy sauce', '13.80'],
            ],
            'Garnalen gerechten (met witte rijst)' => [
                ['98', 'Garnalen met champignons in knoflooksaus', 'Prawns with mushrooms in garlic sauce', '', '', '15.90'],
                ['99', 'Garnalen met tomatensaus', 'Prawns with tomato sauce', '', '', '15.90'],
                ['100', 'Garnalen met ketjapsaus', 'Prawns with soy sauce', '', '', '15.90'],
                ['101', 'Garnalen met broccoli', 'Prawns with broccoli', '', '', '16.10'],
                ['102', 'Garnalen met Chinese champignons', 'Prawns with Chinese mushrooms', '', '', '16.10'],
                ['103', 'Garnalen met kerriesaus', 'Prawns with curry sauce', '', '', '16.10'],
                ['104', 'Garnalen met zwarte bonensaus', 'Prawns with black bean sauce', '', '', '16.10'],
                ['105', 'Garnalen met zwarte pepersaus', 'Prawns with black pepper sauce', '', '', '16.10'],
                ['106', 'Garnalen met chilisaus', 'Prawns with chili sauce', '', '', '16.10'],
                ['107', 'Yu Sian Haa', 'Yu sian haa', 'garnalen met licht zoet pikante kruidensaus', 'prawns in a mildly sweet and spicy herb sauce', '16.10'],
                ['108', 'Tjieuw Yem Haa', 'Tjieuw yem haa', 'licht gebraden garnalen met zout en peper', 'lightly fried prawns with salt and pepper', '16.10'],
                ['109', 'Tja Tai Haa', 'Tja tai haa', 'krokant gebakken garnalen', 'crispy fried prawns', '16.10'],
                ['110', 'Sze Chuan Haa', 'Sze chuan haa', '', '', '16.40'],
            ],
            'Ossenhaas gerechten (met witte rijst)' => [
                ['111', 'Ossenhaas met champignons in knoflooksaus', 'Beef tenderloin with mushrooms in garlic sauce', '', '', '16.90'],
                ['112', 'Ossenhaas met tomatensaus', 'Beef tenderloin with tomato sauce', '', '', '16.90'],
                ['113', 'Ossenhaas met ketjapsaus', 'Beef tenderloin with soy sauce', '', '', '16.90'],
                ['114', 'Ossenhaas met broccoli', 'Beef tenderloin with broccoli', '', '', '17.10'],
                ['115', 'Ossenhaas met Chinese champignons', 'Beef tenderloin with Chinese mushrooms', '', '', '17.10'],
                ['116', 'Ossenhaas met kerriesaus', 'Beef tenderloin with curry sauce', '', '', '17.10'],
                ['117', 'Ossenhaas met zwarte bonensaus', 'Beef tenderloin with black bean sauce', '', '', '17.10'],
                ['118', 'Ossenhaas met zwarte pepersaus', 'Beef tenderloin with black pepper sauce', '', '', '17.10'],
                ['119', 'Yu Sian Ngau Yuk', 'Yu sian ngau yuk', 'ossenhaas met licht zoet pikante kruidensaus', 'beef tenderloin in a mildly sweet and spicy herb sauce', '17.10'],
                ['120', 'Sze Chuan Ngau Yuk', 'Sze chuan ngau yuk', 'ossenhaas met pittige kruidensaus', 'beef tenderloin in a spicy herb sauce', '17.40'],
            ],
            'Vissen gerechten (met witte rijst)' => [
                ['121', 'Visfilet met kerriesaus', 'Fish fillet with curry sauce', '', '', '14.50'],
                ['122', 'Visfilet met oestersaus', 'Fish fillet with oyster sauce', '', '', '14.50'],
                ['123', 'Visfilet met zoetzure saus', 'Fish fillet with sweet and sour sauce', 'licht gebraden visfilet in zoete pikante saus', 'lightly fried fish fillet in a sweet and spicy sauce', '14.50'],
                ['124', 'Hong Shau Yu', 'Hong shau yu', 'licht gebraden visfilet in zoete pikante saus', 'lightly fried fish fillet in a sweet and spicy sauce', '14.50'],
                ['125', 'Tjeuw Yem Yu', 'Tjeuw yem yu', 'licht gebraden visfilet met zout en peper', 'lightly fried fish fillet with salt and pepper', '15.00'],
                ['126', 'San Sching Po', 'San sching po', 'visfilet, garnalen, krab en groenten in knoflooksaus', 'fish fillet, prawns, crab and vegetables in garlic sauce', '16.10'],
            ],
            'Peking eend gerechten (met witte rijst)' => [
                ['P1', 'Geroosterde Peking Eend', 'Roasted Peking duck', '', '', '16.60'],
                ['P2', 'Peking Eend met verse ananas in zoetzure saus', 'Peking duck with fresh pineapple in sweet and sour sauce', '', '', '17.10'],
                ['P3', 'Peking Eend met Chinese champignons in oestersaus', 'Peking duck with Chinese mushrooms in oyster sauce', '', '', '17.10'],
                ['P4', 'Yu Sian Ya', 'Yu sian ya', 'peking eend met licht zoet pikante kruidensaus', 'Peking duck in a mildly sweet and spicy herb sauce', '17.10'],
            ],
            'Tiepan gerechten (met witte rijst)' => [
                ['T1', 'Tiepan Ling Fa', 'Tiepan Ling Fa', 'garnalen, kipfilet, ossenhaas en groenten in zwarte pepersaus', 'prawns, chicken fillet, beef tenderloin and vegetables in black pepper sauce', '17.90'],
                ['T2', 'Tiepan Kai', 'Tiepan kai', 'licht gebraden kipfilet en groenten met zoet pikante saus', 'lightly fried chicken fillet and vegetables in a sweet and spicy sauce', '15.30'],
                ['T3', 'Tiepan San Yuk', 'Tiepan san yuk', 'licht gebraden varkenshaas, kipfilet, ossenhaas en groenten met zoet pikante saus', 'lightly fried pork tenderloin, chicken fillet, beef tenderloin and vegetables in a sweet and spicy sauce', '17.10'],
                ['T4', 'Tiepan Haa', 'Tiepan haa', 'garnalen en groenten met zoet pikante saus', 'prawns and vegetables in a sweet and spicy sauce', '17.40'],
                ['T5', 'Tiepan Ngau Yuk', 'Tiepan ngau yuk', '5 st. ossenhaas en groenten met zoet pikante saus', '5 pcs beef tenderloin and vegetables in a sweet and spicy sauce', '19.50'],
                ['V4', 'Tau Fu Po', 'Tau fu po', 'sojakaas, cha sieuw, garnalen en Chinese paddenstoelen', 'tofu, cha sieuw, prawns and Chinese mushrooms', '15.30'],
            ],
            'Vegetarische gerechten (met witte rijst)' => [
                ['V1', 'Vegetarische Tjap Tjoy', 'Vegetarian chop suey', '', '', '8.30'],
                ['V2', 'Lo Han Zhai', 'Lo han zhai', 'sojakaas, Chinese paddenstoelen en groenten in knoflooksaus', 'tofu, Chinese mushrooms and vegetables in garlic sauce', '11.20'],
                ['V3', 'Vegetarische Foe Yong Hai', 'Vegetarian foe yong hai', '', '', '8.30'],
            ],
            'Kindermenu\'s' => [
                ['K1', 'Frites, saté (2 st.) en ei', 'Fries, satay (2 pcs) and egg', '', '', '6.50'],
                ['K2', 'Frites, kippenpootje en ei', 'Fries, chicken drumstick and egg', '', '', '6.50'],
                ['K3', 'Frites, mini loempia (2 st.) en ei', 'Fries, mini spring rolls (2 pcs) and egg', '', '', '6.50'],
                ['K4', 'Kinder Bami of Nasi met saté (2 st.) en ei', 'Children\'s fried noodles or rice with satay (2 pcs) and egg', '', '', '6.50'],
            ],
            'Rijsttafels' => [
                ['R1', 'Indische rijsttafel (voor 1 persoon)', 'Indonesian rijsttafel (for 1 person)', 'Gado gado, Foe Yong Hai, saté, Daging Roedjak, Daging Smoor, Ajam Ketjap, Atjar, Pisang Goreng, Pinda\'s en Cocos', 'Gado gado, foe yong hai, satay, daging roedjak, daging smoor, ajam ketjap, pickled vegetables, fried banana, peanuts and coconut', '16.40'],
                ['R2', 'Indische rijsttafel (vanaf 2 personen, per persoon)', 'Indonesian rijsttafel (from 2 people, per person)', 'Ajam Ketjap, Gado Gado, Daging Smoor, Kroepoek, Daging Roedjak, Foe Yong Hai, Saté, Sambal Goreng Boontjes, Sambal Goreng Kering, Atjar, Pisang Goreng, Pinda en Cocos', 'Ajam ketjap, gado gado, daging smoor, prawn crackers, daging roedjak, foe yong hai, satay, sambal goreng boontjes, sambal goreng kering, pickled vegetables, fried banana, peanuts and coconut', '15.00'],
                ['R3', 'Chinese Indische Rijsttafel (vanaf 4 personen, per persoon)', 'Chinese-Indonesian rijsttafel (from 4 people, per person)', 'Foe Yong Hai, Babi Pangang, Tjap Tjoy, Koe Loe Yuk, Ajam Ketjap, Daging Smoor, Daging Roedjak, Saté, Ei, Kroepoek, Sambal Goreng Boontjes, Atjar, Pisang Goreng, Pinda en Cocos', 'Foe yong hai, babi pangang, chop suey, sweet and sour pork, ajam ketjap, daging smoor, daging roedjak, satay, egg, prawn crackers, sambal goreng boontjes, pickled vegetables, fried banana, peanuts and coconut', '16.50'],
                ['R4', 'Chinese Rijsttafel (vanaf 2 personen, per persoon)', 'Chinese rijsttafel (from 2 people, per person)', 'Kippen- of Tomatensoep, Tjap Tjoy met kipfilet, Koe Loe Yuk, Gebakken garnalen, Babi Pangang, Foe Yong Hai, saté, kroepoek', 'Chicken or tomato soup, chop suey with chicken fillet, sweet and sour pork, fried prawns, babi pangang, foe yong hai, satay, prawn crackers', '17.00'],
                ['R5', 'Kantones Rijsttafel (vanaf 2 personen, per persoon)', 'Cantonese rijsttafel (from 2 people, per person)', 'Wan Tan soep, Chinese Dim Sum (mini loempia, kerrie ko, pangsit goreng, garnalen, pasteitje), Geroosterde Peking Eend, Lychee Kai (licht gebraden kipfilet met lychee in zoetzure saus), Tau Sie Haa (garnalen met zwarte bonensaus)', 'Wonton soup, Chinese dim sum (mini spring roll, kerry ko, fried wonton, prawn pastry), roasted Peking duck, lychee kai (lightly fried chicken fillet with lychee in sweet and sour sauce), tau sie haa (prawns with black bean sauce)', '23.00'],
                ['R6', 'Sze Chuan Rijsttafel (vanaf 2 personen, per persoon)', 'Sze chuan rijsttafel (from 2 people, per person)', 'Peking Soep (pittige lichtzure soep), Chinese Dim Sum (mini loempia, kerrie ko, pangsit goreng, garnalen, pasteitje), Tjieuw Yem Kai (licht gebakken kipfilet met zout en peper), Lychee Yuk (licht gebraden varkensvlees met lychee in zoetzure saus), Yu Sian Ngau Yuk (ossenhaas met licht zoet pikante kruidensaus)', 'Peking soup (spicy and slightly sour soup), Chinese dim sum (mini spring roll, kerry ko, fried wonton, prawns, pastry), tjieuw yem kai (lightly fried chicken fillet with salt and pepper), lychee yuk (lightly fried pork with lychee in sweet and sour sauce), yu sian ngau yuk (beef tenderloin in a mildly sweet and spicy herb sauce)', '23.00'],
            ],
            'Buffet' => [
                ['B1', 'Buffet Menu A (per persoon)', 'Buffet menu A (per person)', 'Mini Loempia\'s, Pisang Goreng, Babi Pangang, Koe Loe Yuk, Foe Yong Hai, Kipfilet met zwarte bonensaus, Tjap Tjoy met kipfilet, saté Ajam', 'Mini spring rolls, fried banana, babi pangang, sweet and sour pork, foe yong hai, chicken fillet with black bean sauce, chop suey with chicken fillet, chicken satay', '12.80'],
                ['B2', 'Buffet Menu B (per persoon)', 'Buffet menu B (per person)', 'Mini Loempia\'s, Pisang Goreng, Babi Pangang, Foe Yong Hai, Kung Bao Kai, Hong Shau Yu, saté Ajam, Ossenhaas met zwarte bonensaus, Kipfilet met kerriesaus', 'Mini spring rolls, fried banana, babi pangang, foe yong hai, kung bao kai, hong shau yu, chicken satay, beef tenderloin with black bean sauce, chicken fillet with curry sauce', '15.00'],
            ],
            'Diversen' => [
                ['D1', 'Bami of Nasi Goreng ipv rijst', 'Fried noodles or fried rice instead of rice', '', '', '0.90'],
                ['D2', 'Mihoen Goreng ipv rijst', 'Fried rice noodles instead of rice', '', '', '2.50'],
                ['D3', 'Chinese Bami ipv rijst', 'Chinese noodles instead of rice', '', '', '2.50'],
            ],
        ];

        foreach ($dishesByKind as $dishKindName => $dishes) {
            $dishKind = $dishKinds->get($dishKindName);

            if (! $dishKind) {
                continue;
            }

            foreach ($dishes as [$menuNumber, $nameNl, $nameEn, $descriptionNl, $descriptionEn, $price]) {
                Dish::create([
                    'menu_number' => $menuNumber,
                    'dish_kind' => $dishKind->id,
                    'name' => ['nl' => $nameNl, 'en' => $nameEn],
                    'description' => ['nl' => $descriptionNl, 'en' => $descriptionEn],
                    'price' => $price,
                ]);
            }
        }
    }
}
