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
                ['1', 'Kippensoep', 'Chicken soup', 'Heldere kippensoep met glasnoedels', 'Clear chicken soup with glass noodles', '3.75'],
                ['2', 'Kip-tomatensoep', 'Chicken tomato soup', 'Heldere soep met kip, tomaat en ei', 'Clear soup with chicken, tomato and egg', '3.75'],
                ['3', 'Krab-champignonsoep', 'Crab mushroom soup', 'Gebonden soep met surimi en champignons', 'Thickened soup with surimi and mushrooms', '3.95'],
                ['4', 'Wonton soep', 'Wonton soup', 'Heldere soep met gevulde wontons', 'Clear soup with filled wontons', '4.25'],
            ],
            'Voorgerecht' => [
                ['5', 'Loempia', 'Spring roll', 'Krokante loempia met groente', 'Crispy spring roll with vegetables', '2.75'],
                ['6', 'Kroepoek', 'Prawn crackers', 'Portie kroepoek', 'Portion of prawn crackers', '2.25'],
                ['7', 'Saté van varkensvlees', 'Pork satay', 'Vier spiesjes met pindasaus', 'Four skewers with peanut sauce', '6.50'],
                ['8', 'Gefrituurde inktvisringen', 'Deep-fried squid rings', 'Met zoetzure saus', 'Served with sweet and sour sauce', '6.95'],
            ],
            'Eiergerechten' => [
                ['9', 'Foe Yong Hai met kip', 'Foe yong hai with chicken', 'Omelet met taugé en kip, in saus', 'Omelette with beansprouts and chicken, in sauce', '9.50'],
                ['10', 'Foe Yong Hai met garnalen', 'Foe yong hai with prawns', 'Omelet met taugé en garnalen, in saus', 'Omelette with beansprouts and prawns, in sauce', '10.50'],
                ['11', 'Foe Yong Hai met vlees', 'Foe yong hai with beef', 'Omelet met taugé en rundvlees, in saus', 'Omelette with beansprouts and beef, in sauce', '9.95'],
            ],
            'Bami & Nasi gerechten' => [
                ['12', 'Bami goreng speciaal', 'Special fried noodles', 'Gebakken bami met kip, garnaal en ei', 'Fried noodles with chicken, prawn and egg', '9.50'],
                ['13', 'Nasi goreng speciaal', 'Special fried rice', 'Gebakken rijst met kip, garnaal en ei', 'Fried rice with chicken, prawn and egg', '9.50'],
                ['14', 'Bami goreng kip', 'Fried noodles with chicken', 'Gebakken bami met kipfilet', 'Fried noodles with chicken breast', '8.50'],
                ['15', 'Nasi goreng kip', 'Fried rice with chicken', 'Gebakken rijst met kipfilet', 'Fried rice with chicken breast', '8.50'],
            ],
            'Combinatie gerechten (met witte rijst)' => [
                ['16', 'Koe Loe Yuk', 'Sweet and sour pork', 'Zoetzuur varkensvlees met ananas en paprika', 'Sweet and sour pork with pineapple and pepper', '10.50'],
                ['17', 'Tjap Tjoy met vlees', 'Chop suey with meat', 'Gemengde groenten met varkensvlees', 'Mixed vegetables with pork', '10.50'],
                ['18', 'Babi Pangang', 'Babi pangang', 'Krokant gebakken varkensvlees met zoetzure saus', 'Crispy pork with sweet and sour sauce', '10.95'],
                ['19', 'Kipfilet in zwarte bonensaus', 'Chicken fillet in black bean sauce', 'Kipfilet met paprika in pittige bonensaus', 'Chicken fillet with pepper in spicy black bean sauce', '10.95'],
                ['20', 'Garnalen met gebakken knoflook', 'Prawns with fried garlic', 'Grote garnalen gewokt met verse knoflook', 'Large prawns wok-fried with fresh garlic', '12.50'],
            ],
            'Kipgerechten (met witte rijst)' => [
                ['21', 'Kipfilet met cashewnoten', 'Chicken fillet with cashew nuts', 'Kipfilet gewokt met cashewnoten en groente', 'Chicken fillet wok-fried with cashew nuts and vegetables', '10.95'],
                ['22', 'Kipfilet met champignons', 'Chicken fillet with mushrooms', 'Kipfilet in champignonsaus', 'Chicken fillet in mushroom sauce', '10.50'],
                ['23', 'Kipfilet in kerriesaus', 'Chicken fillet in curry sauce', 'Kipfilet in milde kerriesaus', 'Chicken fillet in mild curry sauce', '10.50'],
            ],
            'Garnalen gerechten (met witte rijst)' => [
                ['24', 'Garnalen in kerriesaus', 'Prawns in curry sauce', 'Garnalen in milde kerriesaus', 'Prawns in mild curry sauce', '12.50'],
                ['25', 'Garnalen met gemengde groenten', 'Prawns with mixed vegetables', 'Garnalen gewokt met seizoensgroente', 'Prawns wok-fried with seasonal vegetables', '12.50'],
                ['26', 'Grote garnalen in tomatensaus', 'King prawns in tomato sauce', 'Grote garnalen in pittige tomatensaus', 'King prawns in spicy tomato sauce', '13.95'],
            ],
            'Vegetarische gerechten (met witte rijst)' => [
                ['27', 'Tofu met gemengde groenten', 'Tofu with mixed vegetables', 'Gebakken tofu gewokt met seizoensgroente', 'Fried tofu wok-fried with seasonal vegetables', '8.95'],
                ['28', 'Chinese kool met paddenstoelen', 'Chinese cabbage with mushrooms', 'Chinese kool gewokt met gemengde paddenstoelen', 'Chinese cabbage wok-fried with mixed mushrooms', '8.95'],
                ['29', 'Taugé met bamboe', 'Beansprouts with bamboo shoots', 'Taugé gewokt met bamboescheuten', 'Beansprouts wok-fried with bamboo shoots', '8.50'],
            ],
            "Kindermenu's" => [
                ['30', 'Mini bami met kipsaté', 'Mini noodles with chicken satay', 'Kleine portie bami met een kipsaté spiesje', 'Small portion of noodles with a chicken satay skewer', '6.95'],
                ['31', 'Mini nasi met frikandel', 'Mini rice with frikandel', 'Kleine portie nasi met frikandel', 'Small portion of rice with frikandel', '6.95'],
            ],
            'Rijsttafels' => [
                ['32', 'Rijsttafel "De Gouden Draak" (2 personen)', 'Rice table "De Gouden Draak" (2 people)', 'Keuze uit 3 gerechten met witte rijst', 'Choice of 3 dishes served with white rice', '21.00'],
                ['33', 'Rijsttafel Deluxe (2 personen)', 'Deluxe rice table (2 people)', 'Keuze uit 5 gerechten met witte en gebakken rijst', 'Choice of 5 dishes served with white and fried rice', '27.50'],
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
