/**
 * AdminLTE Demo Menu

 * You should not use this file in production.
 * This file is for demo purposes only.
 */
(function ($, AdinLTE) {

  "use strict";

  /**
   * List of all the available skins
   *
   * @type Array
   */
  var my_skins = [
    "skin-blue",
    "skin-black",
    "skin-red",
    "skin-yellow",
    "skin-purple",
    "skin-green",
    "skin-blue-light",
    "skin-black-light",
    "skin-red-light",
    "skin-yellow-light",
    "skin-purple-light",
    "skin-green-light"
  ];

  //Create the new tab
  var tab_pane = $("<DIV />", {
    "id": "control-sidebar-theme-demo-options-tab",
    "class": "tab-pane active"
  });

  //Create the tab button
  var tab_button = $("<LI />", {"class": "active"})
      .html("<a href='#control-sidebar-theme-demo-options-tab' DATA-TOGGLE='tab'>"
      + "<I CLASS='fa fa-wrench'></I>"
      + "</a>");

  //Add the tab button to the right sidebar tabs
  $("[href='#control-sidebar-home-tab']")
      .parent()
      .before(tab_button);

  //Create the menu
  var demo_settings = $("<DIV />");

  //Layout options
  demo_settings.append(
      "<H4 CLASS='control-sidebar-heading'>"
      + "Opções Layout"
      + "</H4>"
        //Fixed layout
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-LAYOUT='fixed' class='pull-right'/> "
      + "Layout fixo"
      + "</LABEL>"
      + "<P>Ativar o layout fixo . Você não pode usar layouts fixos e box em conjunto!</P>"
      + "</DIV>"
        //Boxed layout
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-LAYOUT='layout-boxed'class='pull-right'/> "
      + "Boxed Layout"
      + "</LABEL>"
      + "<P>Layout em caixa!</P>"
      + "</DIV>"
        //Sidebar Toggle
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-LAYOUT='sidebar-collapse' class='pull-right'/> "
      + "Encolher menu da esquerda"
      + "</LABEL>"
      + "<P>Mostra somente os ícones</P>"
      + "</DIV>"
        //Sidebar mini expand on hover toggle
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-ENABLE='expandOnHover' class='pull-right'/> "
      + "Expandir menu da esquerda ao passar o mouse"
      + "</LABEL>"
      + "<P>Mostra o menu da esquerda completo ao passar o mouse!</P>"
      + "</DIV>"
        //Control Sidebar Toggle
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-CONTROLSIDEBAR='control-sidebar-open' class='pull-right'/> "
      + "Fixar/desfixar este painel"
      + "</LABEL>"
      + "<P>Fixa este painel tornando-o parte do layout!</P>"
      + "</DIV>"
        //Control Sidebar Skin Toggle
      + "<DIV CLASS='form-group'>"
      + "<LABEL CLASS='control-sidebar-subheading'>"
      + "<input type='checkbox' DATA-SIDEBARSKIN='toggle' class='pull-right'/> "
      + "Invertes cores deste menu"
      + "</LABEL>"
      + "<P>Inverte as cores deste menu!</P>"
      + "</DIV>"
  );
  var skins_list = $("<UL />", {"class": 'list-unstyled clearfix'});

  //Dark sidebar skins
  var skin_blue =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-blue' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></SPAN><SPAN CLASS='bg-light-blue' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Blue</P>");
  skins_list.append(skin_blue);
  var skin_black =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-black' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV STYLE='box-shadow: 0 0 2px rgba(0,0,0,0.1)' CLASS='clearfix'><SPAN STYLE='display:block; width: 20%; float: left; height: 7px; background: #242A30;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 7px; background: #242A30;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Black</P>");
  skins_list.append(skin_black);
  var skin_purple =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-purple' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-purple-active'></SPAN><SPAN CLASS='bg-purple' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Purple</P>");
  skins_list.append(skin_purple);
  var skin_green =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-green' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-green-active'></SPAN><SPAN CLASS='bg-green' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Green</P>");
  skins_list.append(skin_green);
  var skin_red =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-red' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-red-active'></SPAN><SPAN CLASS='bg-red' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Red</P>");
  skins_list.append(skin_red);
  var skin_yellow =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-yellow' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-yellow-active'></SPAN><SPAN CLASS='bg-yellow' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin'>Yellow</P>");
  skins_list.append(skin_yellow);

  //Light sidebar skins
  var skin_blue_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-blue-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></SPAN><SPAN CLASS='bg-light-blue' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px'>Blue Light</P>");
  skins_list.append(skin_blue_light);
  var skin_black_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-black-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV STYLE='box-shadow: 0 0 2px rgba(0,0,0,0.1)' CLASS='clearfix'><SPAN STYLE='display:block; width: 20%; float: left; height: 7px; background: #fefefe;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 7px; background: #fefefe;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px'>Light</P>");
  skins_list.append(skin_black_light);
  var skin_purple_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-purple-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-purple-active'></SPAN><SPAN CLASS='bg-purple' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px'>Purple Light</P>");
  skins_list.append(skin_purple_light);
  var skin_green_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-green-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-green-active'></SPAN><SPAN CLASS='bg-green' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px'>Green Light</P>");
  skins_list.append(skin_green_light);
  var skin_red_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-red-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-red-active'></SPAN><SPAN CLASS='bg-red' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px'>Red Light</P>");
  skins_list.append(skin_red_light);
  var skin_yellow_light =
      $("<LI />", {style: "float:left; width: 33.33333%; padding: 5px;"})
          .append("<a href='javascript:void(0);' DATA-SKIN='skin-yellow-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 7px;' CLASS='bg-yellow-active'></SPAN><SPAN CLASS='bg-yellow' STYLE='display:block; width: 80%; float: left; height: 7px;'></SPAN></DIV>"
          + "<DIV><SPAN STYLE='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></SPAN><SPAN STYLE='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></SPAN></DIV>"
          + "</a>"
          + "<P CLASS='text-center no-margin' STYLE='font-size: 12px;'>Yellow Light</P>");
  skins_list.append(skin_yellow_light);

  demo_settings.append("<H4 CLASS='control-sidebar-heading'>Skins</H4>");
  demo_settings.append(skins_list);

  tab_pane.append(demo_settings);
  $("#control-sidebar-home-tab").after(tab_pane);

  setup();

  /**
   * Toggles layout classes
   *
   * @param String cls the layout class to toggle
   * @returns void
   */
  function change_layout(cls) {
    $("body").toggleClass(cls);
    AdminLTE.layout.fixSidebar();
    //Fix the problem with right sidebar and layout boxed
    if (cls == "layout-boxed")
      AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));
    if ($('body').hasClass('fixed') && cls == 'fixed') {
      AdminLTE.pushMenu.expandOnHover();
      AdminLTE.layout.activate();
    }
    AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));
    AdminLTE.controlSidebar._fix($(".control-sidebar"));
  }

  /**
   * Replaces the old skin with the new skin
   * @param String cls the new skin class
   * @returns Boolean false to prevent link's default action
   */
  function change_skin(cls) {
    $.each(my_skins, function (i) {
      $("body").removeClass(my_skins[i]);
    });

    $("body").addClass(cls);
    store('skin', cls);
    return false;
  }

  /**
   * Store a new settings in the browser
   *
   * @param String name Name of the setting
   * @param String val Value of the setting
   * @returns void
   */
  function store(name, val) {
    if (typeof (Storage) !== "undefined") {
      localStorage.setItem(name, val);
    } else {
      window.alert('Atualize seu navegador para uma versão mais moderna!');
    }
  }

  /**
   * Get a prestored setting
   *
   * @param String name Name of of the setting
   * @returns String The value of the setting | null
   */
  function get(name) {
    if (typeof (Storage) !== "undefined") {
      return localStorage.getItem(name);
    } else {
      window.alert('Atualize seu navegador para uma versão mais moderna!');
    }
  }

  /**
   * Retrieve default settings and apply them to the template
   *
   * @returns void
   */
  function setup() {
    var tmp = get('skin');
    if (tmp && $.inArray(tmp, my_skins))
      change_skin(tmp);

    //Add the change skin listener
    $("[data-skin]").on('click', function (e) {
      e.preventDefault();
      change_skin($(this).data('skin'));
    });

    //Add the layout manager
    $("[data-layout]").on('click', function () {
      change_layout($(this).data('layout'));
    });

    $("[data-controlsidebar]").on('click', function () {
      change_layout($(this).data('controlsidebar'));
      var slide = !AdminLTE.options.controlSidebarOptions.slide;
      AdminLTE.options.controlSidebarOptions.slide = slide;
      if (!slide)
        $('.control-sidebar').removeClass('control-sidebar-open');
    });

    $("[data-sidebarskin='toggle']").on('click', function () {
      var sidebar = $(".control-sidebar");
      if (sidebar.hasClass("control-sidebar-dark")) {
        sidebar.removeClass("control-sidebar-dark")
        sidebar.addClass("control-sidebar-light")
      } else {
        sidebar.removeClass("control-sidebar-light")
        sidebar.addClass("control-sidebar-dark")
      }
    });

    $("[data-enable='expandOnHover']").on('click', function () {
      $(this).attr('disabled', true);
      AdminLTE.pushMenu.expandOnHover();
      if (!$('body').hasClass('sidebar-collapse'))
        $("[data-layout='sidebar-collapse']").click();
    });

    // Reset options
    if ($('body').hasClass('fixed')) {
      $("[data-layout='fixed']").attr('checked', 'checked');
    }
    if ($('body').hasClass('layout-boxed')) {
      $("[data-layout='layout-boxed']").attr('checked', 'checked');
    }
    if ($('body').hasClass('sidebar-collapse')) {
      $("[data-layout='sidebar-collapse']").attr('checked', 'checked');
    }

  }
})(jQuery, $.AdminLTE);
