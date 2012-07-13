<?php

//---------------------------------------------------------------------------------
//--------------------- Application Classes----------------------------------------
//------------------------- Definitions -------------------------------------------
//---------------------------------------------------------------------------------

//Administratorzy

//AdminIndex
$config['ioc']['AdminIndexIndex']['className'] = 'AdminIndexIndex';
$config['ioc']['AdminIndexIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&UserMapper', '&ViewResolver', '&Paginator');


//User
$config['ioc']['AdminUserIndex']['className'] = 'AdminUserIndex';
$config['ioc']['AdminUserIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminUserList']['className'] = 'AdminUserList';
$config['ioc']['AdminUserList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminUserView']['className'] = 'AdminUserView';
$config['ioc']['AdminUserView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminUserAddForm']['className'] = 'AdminUserAddForm';
$config['ioc']['AdminUserAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminUserAdd']['className'] = 'AdminUserAdd';
$config['ioc']['AdminUserAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper');

$config['ioc']['AdminUserDelete']['className'] = 'AdminUserDelete';
$config['ioc']['AdminUserDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper');

$config['ioc']['AdminUserEditForm']['className'] = 'AdminUserEditForm';
$config['ioc']['AdminUserEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminUserEdit']['className'] = 'AdminUserEdit';
$config['ioc']['AdminUserEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper');


//Group
$config['ioc']['AdminGroupIndex']['className'] = 'AdminGroupIndex';
$config['ioc']['AdminGroupIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminGroupList']['className'] = 'AdminGroupList';
$config['ioc']['AdminGroupList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminGroupView']['className'] = 'AdminGroupView';
$config['ioc']['AdminGroupView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminGroupAddForm']['className'] = 'AdminGroupAddForm';
$config['ioc']['AdminGroupAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminGroupAdd']['className'] = 'AdminGroupAdd';
$config['ioc']['AdminGroupAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper');

$config['ioc']['AdminGroupEditForm']['className'] = 'AdminGroupEditForm';
$config['ioc']['AdminGroupEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminGroupEdit']['className'] = 'AdminGroupEdit';
$config['ioc']['AdminGroupEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper');

$config['ioc']['AdminGroupDelete']['className'] = 'AdminGroupDelete';
$config['ioc']['AdminGroupDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&GroupMapper');


//Resource
$config['ioc']['AdminResourceIndex']['className'] = 'AdminResourceIndex';
$config['ioc']['AdminResourceIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminResourceList']['className'] = 'AdminResourceList';
$config['ioc']['AdminResourceList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminResourceView']['className'] = 'AdminResourceView';
$config['ioc']['AdminResourceView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminResourceDelete']['className'] = 'AdminResourceDelete';
$config['ioc']['AdminResourceDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper');

$config['ioc']['AdminResourceAddForm']['className'] = 'AdminResourceAddForm';
$config['ioc']['AdminResourceAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminResourceAdd']['className'] = 'AdminResourceAdd';
$config['ioc']['AdminResourceAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper');

$config['ioc']['AdminResourceEditForm']['className'] = 'AdminResourceEditForm';
$config['ioc']['AdminResourceEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['AdminResourceEdit']['className'] = 'AdminResourceEdit';
$config['ioc']['AdminResourceEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ResourceMapper', '&GroupMapper');


//Categories
$config['ioc']['AdminCategoryIndex']['className'] = 'AdminCategoryIndex';
$config['ioc']['AdminCategoryIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminCategoryList']['className'] = 'AdminCategoryList';
$config['ioc']['AdminCategoryList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminCategoryView']['className'] = 'AdminCategoryView';
$config['ioc']['AdminCategoryView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&ViewResolver');

$config['ioc']['AdminCategoryAddForm']['className'] = 'AdminCategoryAddForm';
$config['ioc']['AdminCategoryAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper', '&ViewResolver');

$config['ioc']['AdminCategoryAdd']['className'] = 'AdminCategoryAdd';
$config['ioc']['AdminCategoryAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper');

$config['ioc']['AdminCategoryEditForm']['className'] = 'AdminCategoryEditForm';
$config['ioc']['AdminCategoryEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper', '&ViewResolver');

$config['ioc']['AdminCategoryEdit']['className'] = 'AdminCategoryEdit';
$config['ioc']['AdminCategoryEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper');

$config['ioc']['AdminCategoryDelete']['className'] = 'AdminCategoryDelete';
$config['ioc']['AdminCategoryDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&CategoryMapper');


//Ingredients
$config['ioc']['AdminIngredientIndex']['className'] = 'AdminIngredientIndex';
$config['ioc']['AdminIngredientIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminIngredientList']['className'] = 'AdminIngredientList';
$config['ioc']['AdminIngredientList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminIngredientView']['className'] = 'AdminIngredientView';
$config['ioc']['AdminIngredientView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ItemMapper', '&IngredientMapper', '&ViewResolver');

$config['ioc']['AdminIngredientAddForm']['className'] = 'AdminIngredientAddForm';
$config['ioc']['AdminIngredientAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper', '&ViewResolver');

$config['ioc']['AdminIngredientAdd']['className'] = 'AdminIngredientAdd';
$config['ioc']['AdminIngredientAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper');

$config['ioc']['AdminIngredientEditForm']['className'] = 'AdminIngredientEditForm';
$config['ioc']['AdminIngredientEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper', '&ViewResolver');

$config['ioc']['AdminIngredientEdit']['className'] = 'AdminIngredientEdit';
$config['ioc']['AdminIngredientEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper');

$config['ioc']['AdminIngredientDelete']['className'] = 'AdminIngredientDelete';
$config['ioc']['AdminIngredientDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&IngredientMapper');


//Units
$config['ioc']['AdminUnitIndex']['className'] = 'AdminUnitIndex';
$config['ioc']['AdminUnitIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminUnitList']['className'] = 'AdminUnitList';
$config['ioc']['AdminUnitList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminUnitView']['className'] = 'AdminUnitView';
$config['ioc']['AdminUnitView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper', '&ViewResolver');

$config['ioc']['AdminUnitAddForm']['className'] = 'AdminUnitAddForm';
$config['ioc']['AdminUnitAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper', '&ViewResolver');

$config['ioc']['AdminUnitAdd']['className'] = 'AdminUnitAdd';
$config['ioc']['AdminUnitAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper');

$config['ioc']['AdminUnitEditForm']['className'] = 'AdminUnitEditForm';
$config['ioc']['AdminUnitEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper', '&ViewResolver');

$config['ioc']['AdminUnitEdit']['className'] = 'AdminUnitEdit';
$config['ioc']['AdminUnitEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper');

$config['ioc']['AdminUnitDelete']['className'] = 'AdminUnitDelete';
$config['ioc']['AdminUnitDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UnitMapper');


//Recipes
$config['ioc']['AdminRecipeIndex']['className'] = 'AdminRecipeIndex';
$config['ioc']['AdminRecipeIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&UserMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminRecipeList']['className'] = 'AdminRecipeList';
$config['ioc']['AdminRecipeList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&UserMapper', '&ViewResolver', '&Paginator');

$config['ioc']['AdminRecipeAddForm']['className'] = 'AdminRecipeAddForm';
$config['ioc']['AdminRecipeAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&UserMapper', '&ViewResolver');

$config['ioc']['AdminRecipeAdd']['className'] = 'AdminRecipeAdd';
$config['ioc']['AdminRecipeAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&UserMapper');

$config['ioc']['AdminRecipeView']['className'] = 'AdminRecipeView';
$config['ioc']['AdminRecipeView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['AdminRecipeDelete']['className'] = 'AdminRecipeDelete';
$config['ioc']['AdminRecipeDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['AdminRecipeEditForm']['className'] = 'AdminRecipeEditForm';
$config['ioc']['AdminRecipeEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['AdminRecipeEdit']['className'] = 'AdminRecipeEdit';
$config['ioc']['AdminRecipeEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');


//Menus
$config['ioc']['AdminMenuIndex']['className'] = 'AdminMenuIndex';
$config['ioc']['AdminMenuIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminMenuList']['className'] = 'AdminMenuList';
$config['ioc']['AdminMenuList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminMenuView']['className'] = 'AdminMenuView';
$config['ioc']['AdminMenuView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminMenuAddForm']['className'] = 'AdminMenuAddForm';
$config['ioc']['AdminMenuAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminMenuAdd']['className'] = 'AdminMenuAdd';
$config['ioc']['AdminMenuAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper');

$config['ioc']['AdminMenuEditForm']['className'] = 'AdminMenuEditForm';
$config['ioc']['AdminMenuEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminMenuEdit']['className'] = 'AdminMenuEdit';
$config['ioc']['AdminMenuEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper');

$config['ioc']['AdminMenuDelete']['className'] = 'AdminMenuDelete';
$config['ioc']['AdminMenuDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&MenuMapper');


//Entries
$config['ioc']['AdminEntryIndex']['className'] = 'AdminEntryIndex';
$config['ioc']['AdminEntryIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminEntryList']['className'] = 'AdminEntryList';
$config['ioc']['AdminEntryList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminEntryView']['className'] = 'AdminEntryView';
$config['ioc']['AdminEntryView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminEntryAddForm']['className'] = 'AdminEntryAddForm';
$config['ioc']['AdminEntryAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminEntryAdd']['className'] = 'AdminEntryAdd';
$config['ioc']['AdminEntryAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper');

$config['ioc']['AdminEntryDelete']['className'] = 'AdminEntryDelete';
$config['ioc']['AdminEntryDelete']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper');

$config['ioc']['AdminEntryEditForm']['className'] = 'AdminEntryEditForm';
$config['ioc']['AdminEntryEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper', '&ViewResolver');

$config['ioc']['AdminEntryEdit']['className'] = 'AdminEntryEdit';
$config['ioc']['AdminEntryEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&EntryMapper', '&MenuMapper');


//Widgety dla administratorow i pozostalych
$config['ioc']['AdminMenu']['className'] = 'AdminMenu';
$config['ioc']['AdminMenu']['constructorArgs'] = array('&Request', '&Session', '&MenuMapper', '&EntryMapper', '&ViewResolver', '&I18n');

$config['ioc']['PublicMenu']['className'] = 'PublicMenu';
$config['ioc']['PublicMenu']['constructorArgs'] = array('&Request', '&MenuMapper', '&EntryMapper', '&ViewResolver', '&I18n');

$config['ioc']['UserMenu']['className'] = 'UserMenu';
$config['ioc']['UserMenu']['constructorArgs'] = array('&Request', '&Session', '&MenuMapper', '&EntryMapper', '&ViewResolver', '&I18n');

$config['ioc']['ProfileBlock']['className'] = 'ProfileBlock';
$config['ioc']['ProfileBlock']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&ViewResolver');

$config['ioc']['AdminAuthBlock']['className'] = 'AdminAuthBlock';
$config['ioc']['AdminAuthBlock']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&ViewResolver');

$config['ioc']['CategoryMenu']['className'] = 'CategoryMenu';
$config['ioc']['CategoryMenu']['constructorArgs'] = array('&Request', '&CategoryMapper', '&ViewResolver', '&I18n');

$config['ioc']['SearchBlock']['className'] = 'SearchBlock';
$config['ioc']['SearchBlock']['constructorArgs'] = array('&Request', '&I18n', '&CategoryMapper', '&UserMapper', '&ViewResolver');


//Wszyscy uzytkownicy


//Index
$config['ioc']['IndexIndex']['className'] = 'IndexIndex';
$config['ioc']['IndexIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&ViewResolver', '&Paginator');


//Recipe
$config['ioc']['RecipeQuickAddForm']['className'] = 'RecipeQuickAddForm';
$config['ioc']['RecipeQuickAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ViewResolver');

$config['ioc']['RecipeQuickAdd']['className'] = 'RecipeQuickAdd';
$config['ioc']['RecipeQuickAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper');

$config['ioc']['RecipeAddForm']['className'] = 'RecipeAddForm';
$config['ioc']['RecipeAddForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ViewResolver');

$config['ioc']['RecipeAdd']['className'] = 'RecipeAdd';
$config['ioc']['RecipeAdd']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&UserMapper');

$config['ioc']['RecipeEditForm']['className'] = 'RecipeEditForm';
$config['ioc']['RecipeEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['RecipeEdit']['className'] = 'RecipeEdit';
$config['ioc']['RecipeEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['RecipeOwnList']['className'] = 'RecipeOwnList';
$config['ioc']['RecipeOwnList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&ViewResolver', '&Paginator');

$config['ioc']['RecipeView']['className'] = 'RecipeView';
$config['ioc']['RecipeView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&IngredientMapper', '&UnitMapper', '&ItemMapper', '&ViewResolver');

$config['ioc']['RecipeList']['className'] = 'RecipeList';
$config['ioc']['RecipeList']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&ViewResolver', '&Paginator');

$config['ioc']['RecipeIndex']['className'] = 'RecipeIndex';
$config['ioc']['RecipeIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&RecipeMapper', '&CategoryMapper', '&ViewResolver', '&Paginator');

$config['ioc']['RecipeSearch']['className'] = 'RecipeSearch';
$config['ioc']['RecipeSearch']['constructorArgs'] = array('&Request');


//Profile
$config['ioc']['ProfileEditForm']['className'] = 'ProfileEditForm';
$config['ioc']['ProfileEditForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&ViewResolver');

$config['ioc']['ProfileEdit']['className'] = 'ProfileEdit';
$config['ioc']['ProfileEdit']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper');

$config['ioc']['ProfileView']['className'] = 'ProfileView';
$config['ioc']['ProfileView']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['ProfileIndex']['className'] = 'ProfileIndex';
$config['ioc']['ProfileIndex']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&ViewResolver');

$config['ioc']['ProfileLoginForm']['className'] = 'ProfileLoginForm';
$config['ioc']['ProfileLoginForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&ViewResolver');

$config['ioc']['ProfileLogin']['className'] = 'ProfileLogin';
$config['ioc']['ProfileLogin']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&Auth', '&UserMapper');

$config['ioc']['ProfileLogout']['className'] = 'ProfileLogout';
$config['ioc']['ProfileLogout']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&Auth', '&UserMapper');

$config['ioc']['ProfileResetForm']['className'] = 'ProfileResetForm';
$config['ioc']['ProfileResetForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&ViewResolver');

$config['ioc']['ProfileReset']['className'] = 'ProfileReset';
$config['ioc']['ProfileReset']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&Mailer', '%mail.emailFrom%');

$config['ioc']['ProfileRegisterForm']['className'] = 'ProfileRegisterForm';
$config['ioc']['ProfileRegisterForm']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&ViewResolver');

$config['ioc']['ProfileRegister']['className'] = 'ProfileRegister';
$config['ioc']['ProfileRegister']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper', '&GroupMapper', '&Mailer', '%mail.emailFrom%');

$config['ioc']['ProfileActivate']['className'] = 'ProfileActivate';
$config['ioc']['ProfileActivate']['constructorArgs'] = array('&Request', '&Session', '&I18n', '&UserMapper');


//Mappers

$config['ioc']['UserMapper']['className'] = 'UserMapper';
$config['ioc']['UserMapper']['singleton'] = true;
$config['ioc']['UserMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['User']['className'] = 'User';

$config['ioc']['GroupMapper']['className'] = 'GroupMapper';
$config['ioc']['GroupMapper']['singleton'] = true;
$config['ioc']['GroupMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Group']['className'] = 'Group';

$config['ioc']['ResourceMapper']['className'] = 'ResourceMapper';
$config['ioc']['ResourceMapper']['singleton'] = true;
$config['ioc']['ResourceMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Resource']['className'] = 'Resource';

$config['ioc']['CategoryMapper']['className'] = 'CategoryMapper';
$config['ioc']['CategoryMapper']['singleton'] = true;
$config['ioc']['CategoryMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Category']['className'] = 'Category';

$config['ioc']['IngredientMapper']['className'] = 'IngredientMapper';
$config['ioc']['IngredientMapper']['singleton'] = true;
$config['ioc']['IngredientMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Ingredient']['className'] = 'Ingredient';

$config['ioc']['RecipeMapper']['className'] = 'RecipeMapper';
$config['ioc']['RecipeMapper']['singleton'] = true;
$config['ioc']['RecipeMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Recipe']['className'] = 'Recipe';

$config['ioc']['ItemMapper']['className'] = 'ItemMapper';
$config['ioc']['ItemMapper']['singleton'] = true;
$config['ioc']['ItemMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Item']['className'] = 'Item';

$config['ioc']['UnitMapper']['className'] = 'UnitMapper';
$config['ioc']['UnitMapper']['singleton'] = true;
$config['ioc']['UnitMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Unit']['className'] = 'Unit';

$config['ioc']['MenuMapper']['className'] = 'MenuMapper';
$config['ioc']['MenuMapper']['singleton'] = true;
$config['ioc']['MenuMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Menu']['className'] = 'Menu';

$config['ioc']['EntryMapper']['className'] = 'EntryMapper';
$config['ioc']['EntryMapper']['singleton'] = true;
$config['ioc']['EntryMapper']['constructorArgs'] = array('&DataMapperFactory', '&EntityFactory', '&Collection', '&DbEngine', '&Query', '&Inflector', '&ValidatorInput', '&FilterInput');

$config['ioc']['Entry']['className'] = 'Entry';

?>
