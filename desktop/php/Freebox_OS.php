<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('Freebox_OS');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>
<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">

            <div class="cursor eqLogicAction logoPrimary" data-action="eqlogic_standard">
                <i class="fas fa-bullseye"></i>
                <br />
                <span>{{Scan}}<br />{{équipements standard}}</span>
            </div>

            <div class="cursor eqLogicAction logoPrimary" data-action="tile">
                <i class="fas fa-search"></i>
                <br>
                <span>{{Scan}}<br />{{Tiles}}</span>
            </div>
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
            </div>
            <div class="cursor MaFreebox logoSecondary">
                <i class="fas fa-sitemap"></i>
                <br>
                <span>{{Paramètre de la Freebox}}</span>
            </div>
        </div>
        <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
        <legend><i class="fas fa-table"></i> {{Mes equipements}}</legend>
        <div class="eqLogicThumbnailContainer">
            <?php
            foreach ($eqLogics as $eqLogic) {
                switch ($eqLogic->getLogicalId()) {
                    case 'AirPlay':
                    case 'ADSL':
                    case 'Downloads':
                    case 'System':
                    case 'Disque':
                    case 'Phone':
                    case 'Wifi':
                    case 'Reseau':
                        $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                        echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
                        echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
                        echo '<br>';
                        echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                        echo '</div>';
                        break;
                }
            }
            ?>
        </div>

        <legend><i class="fas fa-home"></i> {{Mes Equipements Home - Tiles}}</legend>
        <div class="eqLogicThumbnailContainer">
            <?php
            foreach ($eqLogics as $eqLogic) {
                switch ($eqLogic->getLogicalId()) {
                    case 'AirPlay':
                    case 'ADSL':
                    case 'Downloads':
                    case 'System':
                    case 'Disque':
                    case 'Phone':
                    case 'Wifi':
                    case 'Reseau':
                        break;
                    default:
                        $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                        echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
                        echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
                        echo '<br>';
                        echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                        echo '</div>';
                        break;
                }
            }
            ?>
        </div>
    </div>

    <div class="col-xs-12 eqLogic" style="display: none;">
        <div class="input-group pull-right" style="display:inline-flex">
            <span class="input-group-btn">
                <a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
            </span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation">
                <a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
                    <i class="fas fa-arrow-circle-left"></i>
                </a>
            </li>
            <li role="presentation" class="active">
                <a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
                    <i class="fas fa-tachometer-alt"></i>
                    {{Equipement}}
                </a>
            </li>
            <li role="presentation">
                <a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="fas fa-list-alt"></i>
                    {{Commandes}}
                </a>
            </li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br />
                <form class="form-horizontal col-sm-10">
                    <fieldset>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">{{Nom de l'équipement}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="logicalId" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (jeeObject::all() as $object)
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{Catégorie}}</label>
                            <div class="col-sm-9">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-9">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked />
                                    {{Activer}}
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />
                                    {{Visible}}
                                </label>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">{{Temps de rafraichissement (s)}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="waite" placeholder="{{Temps de rafraichissement (s)}}" />
                            </div>
                        </div>
                        <div class="form-group Equipement">
                            <label class="col-sm-2 control-label">{{Recherche des équipements}}</label>
                            <div class="col-sm-9">
                                <a class="btn btn-primary eqLogicAction"><i class="fas fa-search"></i> {{Recherche}}</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <a class="btn btn-sm cmdAction pull-right Add_Equipement" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une info}}</a>
                <br /><br />
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 10px;"> </th>
                            <th style="width: 650px;">{{Nom}}</th>
                            <th style="width: 110px;">{{Sous-Type}}</th>
                            <th style="width: 350px;">{{Min/Max - Unité}}</th>
                            <th>{{Paramètres}}</th>
                            <th style="width: 250px;">{{Options}}</th>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include_file('desktop', 'Freebox_OS', 'js', 'Freebox_OS');
include_file('core', 'plugin.template', 'js');
?>