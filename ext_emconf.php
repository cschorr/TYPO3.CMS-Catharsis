<?php

########################################################################
# Extension Manager/Repository config file for ext "fluid".
#
# Auto generated 17-11-2009 22:21
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Fluid Templating Engine',
	'description' => 'Fluid is a next-generation templating engine which makes the life of extension authors a lot easier!',
	'category' => 'fe',
	'author' => 'Sebastian Kurfürst, Bastian Waidelich',
	'author_email' => 'sebastian@typo3.org, bastian@typo3.org',
	'shy' => '',
	'dependencies' => 'extbase',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.3.0-alpha1l',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3.0-alpha1',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:179:{s:16:"ext_autoload.php";s:4:"f1d2";s:12:"ext_icon.gif";s:4:"e922";s:21:"Classes/Exception.php";s:4:"c941";s:17:"Classes/Fluid.php";s:4:"0b6b";s:49:"Classes/Compatibility/DocbookGeneratorService.php";s:4:"2b6d";s:39:"Classes/Compatibility/ObjectFactory.php";s:4:"1245";s:47:"Classes/Compatibility/TemplateParserBuilder.php";s:4:"285c";s:26:"Classes/Core/Exception.php";s:4:"29ab";s:33:"Classes/Core/RuntimeException.php";s:4:"68fe";s:33:"Classes/Core/Parser/Exception.php";s:4:"2cd3";s:47:"Classes/Core/Parser/ParsedTemplateInterface.php";s:4:"cab8";s:36:"Classes/Core/Parser/ParsingState.php";s:4:"ad87";s:38:"Classes/Core/Parser/TemplateParser.php";s:4:"21d4";s:47:"Classes/Core/Parser/SyntaxTree/AbstractNode.php";s:4:"d309";s:44:"Classes/Core/Parser/SyntaxTree/ArrayNode.php";s:4:"8b32";s:53:"Classes/Core/Parser/SyntaxTree/ObjectAccessorNode.php";s:4:"f24a";s:43:"Classes/Core/Parser/SyntaxTree/RootNode.php";s:4:"cdad";s:43:"Classes/Core/Parser/SyntaxTree/TextNode.php";s:4:"7b71";s:49:"Classes/Core/Parser/SyntaxTree/ViewHelperNode.php";s:4:"3e76";s:56:"Classes/Core/Rendering/HTMLSpecialCharsPostProcessor.php";s:4:"adc3";s:63:"Classes/Core/Rendering/ObjectAccessorPostProcessorInterface.php";s:4:"d30b";s:49:"Classes/Core/Rendering/RenderingConfiguration.php";s:4:"1d70";s:43:"Classes/Core/Rendering/RenderingContext.php";s:4:"4588";s:46:"Classes/Core/ViewHelper/AbstractViewHelper.php";s:4:"0e53";s:46:"Classes/Core/ViewHelper/ArgumentDefinition.php";s:4:"004d";s:37:"Classes/Core/ViewHelper/Arguments.php";s:4:"e620";s:37:"Classes/Core/ViewHelper/Exception.php";s:4:"521e";s:46:"Classes/Core/ViewHelper/TagBasedViewHelper.php";s:4:"c805";s:38:"Classes/Core/ViewHelper/TagBuilder.php";s:4:"a428";s:53:"Classes/Core/ViewHelper/TemplateVariableContainer.php";s:4:"17ea";s:47:"Classes/Core/ViewHelper/ViewHelperInterface.php";s:4:"e499";s:55:"Classes/Core/ViewHelper/ViewHelperVariableContainer.php";s:4:"6dd0";s:59:"Classes/Core/ViewHelper/Facets/ChildNodeAccessInterface.php";s:4:"2364";s:53:"Classes/Core/ViewHelper/Facets/PostParseInterface.php";s:4:"fdc2";s:36:"Classes/Service/DocbookGenerator.php";s:4:"6c7d";s:26:"Classes/View/Exception.php";s:4:"e88e";s:29:"Classes/View/TemplateView.php";s:4:"5b80";s:38:"Classes/View/TemplateViewInterface.php";s:4:"824d";s:50:"Classes/View/Exception/InvalidTemplateResource.php";s:4:"155b";s:39:"Classes/ViewHelpers/AliasViewHelper.php";s:4:"bfa8";s:38:"Classes/ViewHelpers/BaseViewHelper.php";s:4:"808c";s:41:"Classes/ViewHelpers/CObjectViewHelper.php";s:4:"d009";s:39:"Classes/ViewHelpers/CountViewHelper.php";s:4:"0af0";s:39:"Classes/ViewHelpers/CycleViewHelper.php";s:4:"f358";s:39:"Classes/ViewHelpers/DebugViewHelper.php";s:4:"acfe";s:38:"Classes/ViewHelpers/ElseViewHelper.php";s:4:"3beb";s:37:"Classes/ViewHelpers/ForViewHelper.php";s:4:"8ab4";s:38:"Classes/ViewHelpers/FormViewHelper.php";s:4:"b6c3";s:44:"Classes/ViewHelpers/GroupedForViewHelper.php";s:4:"159f";s:36:"Classes/ViewHelpers/IfViewHelper.php";s:4:"66e7";s:39:"Classes/ViewHelpers/ImageViewHelper.php";s:4:"383d";s:40:"Classes/ViewHelpers/LayoutViewHelper.php";s:4:"bf8f";s:53:"Classes/ViewHelpers/RenderFlashMessagesViewHelper.php";s:4:"abc0";s:40:"Classes/ViewHelpers/RenderViewHelper.php";s:4:"398a";s:41:"Classes/ViewHelpers/SectionViewHelper.php";s:4:"514c";s:38:"Classes/ViewHelpers/ThenViewHelper.php";s:4:"a392";s:43:"Classes/ViewHelpers/TranslateViewHelper.php";s:4:"3d0a";s:52:"Classes/ViewHelpers/Be/AbstractBackendViewHelper.php";s:4:"d9f6";s:46:"Classes/ViewHelpers/Be/ContainerViewHelper.php";s:4:"8197";s:45:"Classes/ViewHelpers/Be/PageInfoViewHelper.php";s:4:"c76c";s:45:"Classes/ViewHelpers/Be/PagePathViewHelper.php";s:4:"9766";s:46:"Classes/ViewHelpers/Be/TableListViewHelper.php";s:4:"e5c2";s:48:"Classes/ViewHelpers/Be/Buttons/CshViewHelper.php";s:4:"c47c";s:49:"Classes/ViewHelpers/Be/Buttons/IconViewHelper.php";s:4:"dd33";s:53:"Classes/ViewHelpers/Be/Buttons/ShortcutViewHelper.php";s:4:"5e87";s:57:"Classes/ViewHelpers/Be/Menus/ActionMenuItemViewHelper.php";s:4:"96c0";s:53:"Classes/ViewHelpers/Be/Menus/ActionMenuViewHelper.php";s:4:"0ad4";s:56:"Classes/ViewHelpers/Form/AbstractFormFieldViewHelper.php";s:4:"126c";s:51:"Classes/ViewHelpers/Form/AbstractFormViewHelper.php";s:4:"a6ec";s:47:"Classes/ViewHelpers/Form/CheckboxViewHelper.php";s:4:"fc39";s:45:"Classes/ViewHelpers/Form/ErrorsViewHelper.php";s:4:"4310";s:45:"Classes/ViewHelpers/Form/HiddenViewHelper.php";s:4:"390e";s:47:"Classes/ViewHelpers/Form/PasswordViewHelper.php";s:4:"0607";s:44:"Classes/ViewHelpers/Form/RadioViewHelper.php";s:4:"db2b";s:45:"Classes/ViewHelpers/Form/SelectViewHelper.php";s:4:"54c4";s:45:"Classes/ViewHelpers/Form/SubmitViewHelper.php";s:4:"29c8";s:47:"Classes/ViewHelpers/Form/TextareaViewHelper.php";s:4:"47d3";s:46:"Classes/ViewHelpers/Form/TextboxViewHelper.php";s:4:"a1b9";s:45:"Classes/ViewHelpers/Form/UploadViewHelper.php";s:4:"0337";s:45:"Classes/ViewHelpers/Format/CropViewHelper.php";s:4:"4c8e";s:49:"Classes/ViewHelpers/Format/CurrencyViewHelper.php";s:4:"0b3a";s:45:"Classes/ViewHelpers/Format/DateViewHelper.php";s:4:"ec9b";s:45:"Classes/ViewHelpers/Format/HtmlViewHelper.php";s:4:"54b9";s:46:"Classes/ViewHelpers/Format/Nl2brViewHelper.php";s:4:"e77a";s:47:"Classes/ViewHelpers/Format/NumberViewHelper.php";s:4:"065d";s:48:"Classes/ViewHelpers/Format/PaddingViewHelper.php";s:4:"6010";s:47:"Classes/ViewHelpers/Format/PrintfViewHelper.php";s:4:"dad3";s:45:"Classes/ViewHelpers/Link/ActionViewHelper.php";s:4:"9f0d";s:44:"Classes/ViewHelpers/Link/EmailViewHelper.php";s:4:"a04a";s:47:"Classes/ViewHelpers/Link/ExternalViewHelper.php";s:4:"8c67";s:43:"Classes/ViewHelpers/Link/PageViewHelper.php";s:4:"38d9";s:44:"Classes/ViewHelpers/Uri/ActionViewHelper.php";s:4:"f8f4";s:43:"Classes/ViewHelpers/Uri/EmailViewHelper.php";s:4:"1046";s:46:"Classes/ViewHelpers/Uri/ExternalViewHelper.php";s:4:"237e";s:42:"Classes/ViewHelpers/Uri/PageViewHelper.php";s:4:"431a";s:46:"Classes/ViewHelpers/Uri/ResourceViewHelper.php";s:4:"c8e4";s:45:"Tests/Unit/Core/ParsingStateTest_testcase.php";s:4:"45b9";s:51:"Tests/Unit/Core/TagBasedViewHelperTest_testcase.php";s:4:"1805";s:43:"Tests/Unit/Core/TagBuilderTest_testcase.php";s:4:"35e7";s:47:"Tests/Unit/Core/TemplateParserTest_testcase.php";s:4:"3af7";s:50:"Tests/Unit/Core/VariableContainerTest_testcase.php";s:4:"1126";s:53:"Tests/Unit/Core/Fixtures/PostParseFacetViewHelper.php";s:4:"5148";s:67:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture01-shorthand.html";s:4:"e949";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture01.html";s:4:"1491";s:67:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture02-shorthand.html";s:4:"fbb8";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture02.html";s:4:"0034";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture03.html";s:4:"1ec8";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture04.html";s:4:"da59";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture05.html";s:4:"d608";s:67:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture06-shorthand.html";s:4:"497b";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture06.html";s:4:"92c2";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture07.html";s:4:"1727";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture08.html";s:4:"b1e4";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture09.html";s:4:"a940";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture10.html";s:4:"12de";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture11.html";s:4:"e279";s:63:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture12_cdata.html";s:4:"c794";s:78:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture13_mandatoryInformation.html";s:4:"4b28";s:57:"Tests/Unit/Core/Fixtures/TemplateParserTestFixture14.html";s:4:"1ec8";s:64:"Tests/Unit/Core/Fixtures/TemplateParserTestPostParseFixture.html";s:4:"c4d7";s:43:"Tests/Unit/Core/Fixtures/TestViewHelper.php";s:4:"253f";s:61:"Tests/Unit/Core/Parser/TemplateParserPatternTest_testcase.php";s:4:"826c";s:66:"Tests/Unit/Core/Parser/Fixtures/ChildNodeAccessFacetViewHelper.php";s:4:"ce95";s:63:"Tests/Unit/Core/Parser/SyntaxTree/AbstractNodeTest_testcase.php";s:4:"218c";s:59:"Tests/Unit/Core/Parser/SyntaxTree/TextNodeTest_testcase.php";s:4:"5344";s:75:"Tests/Unit/Core/Parser/SyntaxTree/ViewHelperNodeComparatorTest_testcase.php";s:4:"1ad5";s:65:"Tests/Unit/Core/Parser/SyntaxTree/ViewHelperNodeTest_testcase.php";s:4:"a1bd";s:72:"Tests/Unit/Core/Rendering/HTMLSpecialCharsPostProcessorTest_testcase.php";s:4:"1145";s:65:"Tests/Unit/Core/Rendering/RenderingConfigurationTest_testcase.php";s:4:"734f";s:59:"Tests/Unit/Core/Rendering/RenderingContextTest_testcase.php";s:4:"d432";s:62:"Tests/Unit/Core/ViewHelper/AbstractViewHelperTest_testcase.php";s:4:"09e7";s:62:"Tests/Unit/Core/ViewHelper/ArgumentDefinitionTest_testcase.php";s:4:"6d79";s:69:"Tests/Unit/Core/ViewHelper/TemplateVariableContainerTest_testcase.php";s:4:"970f";s:71:"Tests/Unit/Core/ViewHelper/ViewHelperVariableContainerTest_testcase.php";s:4:"20ee";s:45:"Tests/Unit/View/TemplateViewTest_testcase.php";s:4:"f629";s:43:"Tests/Unit/View/Fixtures/LayoutFixture.html";s:4:"cca1";s:48:"Tests/Unit/View/Fixtures/TemplateViewFixture.php";s:4:"4082";s:56:"Tests/Unit/View/Fixtures/TemplateViewSectionFixture.html";s:4:"aa5f";s:54:"Tests/Unit/View/Fixtures/TransparentSyntaxTreeNode.php";s:4:"185c";s:53:"Tests/Unit/View/Fixtures/UnparsedTemplateFixture.html";s:4:"59dd";s:55:"Tests/Unit/ViewHelpers/AliasViewHelperTest_testcase.php";s:4:"a9c2";s:54:"Tests/Unit/ViewHelpers/BaseViewHelperTest_testcase.php";s:4:"f7e4";s:55:"Tests/Unit/ViewHelpers/CycleViewHelperTest_testcase.php";s:4:"3081";s:54:"Tests/Unit/ViewHelpers/ElseViewHelperTest_testcase.php";s:4:"931f";s:53:"Tests/Unit/ViewHelpers/ForViewHelperTest_testcase.php";s:4:"b205";s:54:"Tests/Unit/ViewHelpers/FormViewHelperTest_testcase.php";s:4:"58a8";s:60:"Tests/Unit/ViewHelpers/GroupedForViewHelperTest_testcase.php";s:4:"375a";s:52:"Tests/Unit/ViewHelpers/IfViewHelperTest_testcase.php";s:4:"62a4";s:54:"Tests/Unit/ViewHelpers/ThenViewHelperTest_testcase.php";s:4:"6464";s:59:"Tests/Unit/ViewHelpers/TranslateViewHelperTest_testcase.php";s:4:"ac44";s:49:"Tests/Unit/ViewHelpers/ViewHelperBaseTestcase.php";s:4:"c9fa";s:60:"Tests/Unit/ViewHelpers/Fixtures/ConstraintSyntaxTreeNode.php";s:4:"a80b";s:46:"Tests/Unit/ViewHelpers/Fixtures/IfFixture.html";s:4:"8458";s:54:"Tests/Unit/ViewHelpers/Fixtures/IfThenElseFixture.html";s:4:"62f0";s:72:"Tests/Unit/ViewHelpers/Form/AbstractFormFieldViewHelperTest_testcase.php";s:4:"8c26";s:67:"Tests/Unit/ViewHelpers/Form/AbstractFormViewHelperTest_testcase.php";s:4:"092c";s:63:"Tests/Unit/ViewHelpers/Form/CheckboxViewHelperTest_testcase.php";s:4:"e942";s:61:"Tests/Unit/ViewHelpers/Form/ErrorsViewHelperTest_testcase.php";s:4:"4f6b";s:61:"Tests/Unit/ViewHelpers/Form/HiddenViewHelperTest_testcase.php";s:4:"71e3";s:60:"Tests/Unit/ViewHelpers/Form/RadioViewHelperTest_testcase.php";s:4:"0b50";s:61:"Tests/Unit/ViewHelpers/Form/SelectViewHelperTest_testcase.php";s:4:"aa13";s:61:"Tests/Unit/ViewHelpers/Form/SubmitViewHelperTest_testcase.php";s:4:"b4c5";s:63:"Tests/Unit/ViewHelpers/Form/TextareaViewHelperTest_testcase.php";s:4:"2baa";s:62:"Tests/Unit/ViewHelpers/Form/TextboxViewHelperTest_testcase.php";s:4:"ced8";s:61:"Tests/Unit/ViewHelpers/Form/UploadViewHelperTest_testcase.php";s:4:"4868";s:60:"Tests/Unit/ViewHelpers/Form/Fixtures/EmptySyntaxTreeNode.php";s:4:"e8e9";s:64:"Tests/Unit/ViewHelpers/Form/Fixtures/Fixture_UserDomainClass.php";s:4:"4bd0";s:65:"Tests/Unit/ViewHelpers/Format/CurrencyViewHelperTest_testcase.php";s:4:"913c";s:61:"Tests/Unit/ViewHelpers/Format/DateViewHelperTest_testcase.php";s:4:"94ac";s:62:"Tests/Unit/ViewHelpers/Format/Nl2brViewHelperTest_testcase.php";s:4:"aaac";s:63:"Tests/Unit/ViewHelpers/Format/NumberViewHelperTest_testcase.php";s:4:"ac21";s:64:"Tests/Unit/ViewHelpers/Format/PaddingViewHelperTest_testcase.php";s:4:"37c2";s:63:"Tests/Unit/ViewHelpers/Format/PrintfViewHelperTest_testcase.php";s:4:"c290";s:61:"Tests/Unit/ViewHelpers/Link/ActionViewHelperTest_testcase.php";s:4:"0fc0";s:60:"Tests/Unit/ViewHelpers/Link/EmailViewHelperTest_testcase.php";s:4:"3aaa";s:63:"Tests/Unit/ViewHelpers/Link/ExternalViewHelperTest_testcase.php";s:4:"1a76";s:60:"Tests/Unit/ViewHelpers/Uri/ActionViewHelperTest_testcase.php";s:4:"6922";s:59:"Tests/Unit/ViewHelpers/Uri/EmailViewHelperTest_testcase.php";s:4:"5436";s:62:"Tests/Unit/ViewHelpers/Uri/ExternalViewHelperTest_testcase.php";s:4:"adcd";}',
	'suggests' => array(
	),
);

?>
