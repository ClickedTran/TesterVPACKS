<?php
declare(strict_types=1);

namespace DavyCraft648\AnimeQuotes\utils;

use DavyCraft648\AnimeQuotes\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\entity\Attribute;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use vezdehod\packs\PluginContent;
use vezdehod\packs\ui\jsonui\binding\Binding;
use vezdehod\packs\ui\jsonui\binding\BindingType;
use vezdehod\packs\ui\jsonui\binding\DataBinding;
use vezdehod\packs\ui\jsonui\element\ImageElement;
use vezdehod\packs\ui\jsonui\element\ScreenElement;
use vezdehod\packs\ui\jsonui\element\types\Anchor;
use vezdehod\packs\ui\jsonui\element\types\FontType;
use vezdehod\packs\ui\jsonui\element\types\Offset;
use vezdehod\packs\ui\jsonui\element\types\Orientation;
use vezdehod\packs\ui\jsonui\element\types\Size;
use vezdehod\packs\ui\jsonui\element\types\TextAlignment;
use vezdehod\packs\ui\jsonui\element\GridElement;
use vezdehod\packs\ui\jsonui\vanilla\form\SimpleFormDecorator;
use vezdehod\packs\ui\jsonui\vanilla\form\SimpleFormStyle;
use vezdehod\packs\ui\jsonui\variable\Variable;

final class FormUtils{


	public static SimpleFormDecorator $decorator;

	public static function init(PluginContent $content) : void{
		$style = $content->getUI()->getJsonUIs()->createSimpleFromStyle();
$title = $style->addPanel("title")
    ->layer(5)
    ->size(new Size("100%", "10px"))
    ->anchor(Anchor::TOP_RIGHT)
    ->in(fn($elem) => $elem->addLabel("text")
        ->text(ScreenElement::getTitleBinding())
        ->fontType(FontType::MINECRAFTTEN)
        ->textAlignment(TextAlignment::CENTER)
        ->fontScaleFactor(2)
        ->anchor(Anchor::TOP_MIDDLE)
    )
    ->in(fn($elem) => $elem->addExtends("close_button", "common.close_button")->anchor(Anchor::TOP_RIGHT));

$buttonTemplate = $style->addStackPanel("gridded_button", Orientation::VERTICAL)
    ->anchor(Anchor::TOP_RIGHT)
    ->size(new Size("90px", "90px"))
    ->in(fn($elem) => $elem->addImage("image")
        ->size(new Size("45px", "45px"))
        ->layer(2)
        ->binding(DataBinding::overrideFromCollection(
            SimpleFormStyle::getFormButtons(),
            SimpleFormStyle::getButtonTextureBinding(),
            ImageElement::getTextureBinding())
        )
        ->binding(DataBinding::overrideFromCollection(
            SimpleFormStyle::getFormButtons(),
            SimpleFormStyle::getButtonTextureFSBinding(),
            ImageElement::getTextureFSBinding()
        ))
    )
    ->in(fn($elem) => $elem->addExtends("real_button", "common_buttons.light_text_button")
        ->anchor(Anchor::TOP_LEFT)
        ->size(new Size("45px", "20px"))
        ->setVar('pressed_button_name', 'button.form_button_click')
        ->setVar('border_visible', false)
        ->setVar('button_text', SimpleFormStyle::getButtonTextBinding())
        ->setVar('button_text_binding_type', BindingType::COLLECTION)
        ->setVar('button_text_grid_collection_name', SimpleFormStyle::getFormButtons())
        ->setVar('button_text_max_size', new Size("100%", "20px"))
        ->binding(DataBinding::collectionDetails(SimpleFormStyle::getFormButtons()))
    );

$viewport = $style->addGrid("scrolling_view")
    ->anchor(Anchor::TOP_RIGHT)
    ->size(new Size("100%", "100%c"))
    ->offset(new Offset("5px", "5px"))
    ->gridItemTemplate($buttonTemplate->getId())
    ->gridRescalingType(Orientation::HORIZONTAL)
    ->collectionName(SimpleFormStyle::getFormButtons())
    ->binding(DataBinding::override(SimpleFormStyle::getButtonsLengthBinding(), GridElement::getMaximumGridItemsBinding()));

$contents = $style->addExtends("contents", "common.scrolling_panel")
    ->anchor(Anchor::CENTER)
    ->size(new Size("100%", "100%"))
    ->offset(new Offset("0", "-8"))
    ->setVar("show_background", false)
    ->setVar("scrolling_content", $viewport->getId())
    ->setVar("scroll_size", new Size("5px", "100% - 20px"))
    ->setVar("scrolling_pane_size", new Size("100%", "100% - 20px"));

$root = $style->addStackPanel($style->getRootName(), Orientation::VERTICAL)
    ->size(new Size("100% - 50px", "100% - 20px"));
$root->addExtends("title", $title->getId());
$root->addPanel("padding")->size(new Size("100%", "30px"));
$root->addExtends("contents", $contents->getId());

$style->setRootElement($root);

		/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
		self::$decorator = $style->getDecorator();
	}

	public static function sendForm(
		Player $player
	) : void{
		$form = new SimpleForm(function(Player $player, ?int $data) : void{
		});
		$form->setTitle("TESTER");
        $form->addButton("10 ", SimpleForm::IMAGE_TYPE_PATH, 'textures/items/diamond_pickaxe');
        $form->addButton("9 ", SimpleForm::IMAGE_TYPE_PATH, 'textures/items/iron_pickaxe');
        $form->addButton("8 ", SimpleForm::IMAGE_TYPE_PATH, 'textures/items/gold_pickaxe');
        $form->addButton("7 ", SimpleForm::IMAGE_TYPE_PATH, 'textures/items/stone_pickaxe');
        $form->addButton("6 ", SimpleForm::IMAGE_TYPE_PATH, 'textures/items/wood_pickaxe');
        $form->addButton("LOLI", SimpleForm::IMAGE_TYPE_URL, 'https://drive.google.com/file/d/1WSewhmQftomLCPRyMePF8siy5ggxrp0d/view?usp=drive_link');
		$player->sendForm(self::$decorator->decorate($form));

		Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player) : void{
			$player->getAttributeMap()->get(Attribute::EXPERIENCE_LEVEL)->markSynchronized(false);
		}), 20);
	}
}
