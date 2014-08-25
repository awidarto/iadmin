<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
</head>
<body>
    @include('emails.styles')

    <style type="text/css">
    td.panel{
        padding:6px 26px !important;
        text-align:center;
    }

    td.panel h6{
        color: #FFFFFF;
        font-family: serif;
        text-align: center;
        margin-bottom: 6px;
    }

    td.panel img{
        width:99%;
        height:auto;
        margin:auto;
    }

    a.more{
        margin-top: -20px;
        z-index: 100;
        float: left;
    }

    td div.desc{
        margin: 0px 25px;
        min-height: 93px;
        padding: 8px;
        color: #FFFFFF;
        background: url({{ URL::to('img/eletter/desc_bg.png') }}) center top;
    }

    td div.desc p{
        color: #FFFFFF;
    }

    .head-desc td{
        padding: 8px;
    }

    .head-desc p{
        font-weight: normal;
        margin:10px;
        color: #FFFFFF;
    }
    </style>
    <table class="body">
        <tr>
            <td class="center" align="center" valign="top">
        <center>

          <table class="row header">
            <tr>
              <td class="center" align="center">
                <center>

                  <table class="container" style="background: url({{ URL::to('img/eletter/logo_gradient.png') }}) no-repeat">
                    <tr>
                      <td class="wrapper last">

                        <table class="twelve columns">
                          <tr>
                            <td class="seven sub-columns" >
                                <table class="row head-desc" style="margin-top:68px;">
                                    <tr>
                                        <td class="six columns">
                                            <p class="lead">Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                                        </td>
                                        <td class="six columns">
                                            <p class="lead">Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="five sub-columns last" style="text-align:right; vertical-align:middle; max-width:285px;">
                                <img src="{{ URL::to('img/eletter/head_prop.jpg') }}" alt="featured_prop" style="float:right;" />
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>

                      </td>
                    </tr>
                  </table>

                </center>
              </td>
            </tr>
          </table>

          <table class="container">
            <tr>
              <td>

                <table class="row">
                  <tr>
                    <td class="wrapper last">

                      <table class="twelve columns">
                        <tr>
                          <td style="padding: 10px 25px;">
                                <h4>Hi, Susan Calvin</h4>
                                <p class="lead">Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae. consequat vel lacus. Sed iaculis pulvinar ligula, ornare fringilla ante viverra et. In hac habitasse platea dictumst. Donec vel orci mi, eu congue justo. Integer eget odio est, eget malesuada lorem. Aenean sed tellus dui, vitae viverra risus. Nullam massa sapien, pulvinar eleifend fringilla id, convallis eget nisi. Mauris a sagittis dui. Pellentesque non lacinia mi. Fusce sit amet libero sit amet erat venenatis sollicitudin vitae vel eros. Cras nunc sapien, interdum sit amet porttitor ut, congue quis urna.</p>

                                <h5>Another Offer</h5>
                                <p class="lead">Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae. consequat vel lacus. Sed iaculis pulvinar ligula, ornare fringilla ante viverra et. In hac habitasse platea dictumst. Donec vel orci mi, eu congue justo. Integer eget odio est, eget malesuada lorem. Aenean sed tellus dui, vitae viverra risus. Nullam massa sapien, pulvinar eleifend fringilla id, convallis eget nisi. Mauris a sagittis dui. Pellentesque non lacinia mi. Fusce sit amet libero sit amet erat venenatis sollicitudin vitae vel eros. Cras nunc sapien, interdum sit amet porttitor ut, congue quis urna.</p>

                                <h5>Another Offer</h5>
                                <p class="lead">Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae. consequat vel lacus. Sed iaculis pulvinar ligula, ornare fringilla ante viverra et. In hac habitasse platea dictumst. Donec vel orci mi, eu congue justo. Integer eget odio est, eget malesuada lorem. Aenean sed tellus dui, vitae viverra risus. Nullam massa sapien, pulvinar eleifend fringilla id, convallis eget nisi. Mauris a sagittis dui. Pellentesque non lacinia mi. Fusce sit amet libero sit amet erat venenatis sollicitudin vitae vel eros. Cras nunc sapien, interdum sit amet porttitor ut, congue quis urna.</p>

                          </td>
                          <td class="expander"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>

                <table class="row callout">
                  <tr>
                    <td class="wrapper last">

                      <table class="four columns">
                        <tr>
                          <td class="panel" style="background: url({{ URL::to('img/eletter/panel_bg.jpg') }}) no-repeat center top;padding: 0px 10px;">
                            <h6>Antique, Orlando</h6>
                            <img src="http://localhost/iadmin/public/storage/media/LewdgJF9d0j3GmV/full_0000225b_medium.jpeg" alt="IA102345" />
                            <a class="more" href="{{ URL::to('property/detail/IA102345') }}">
                                <img src="{{ URL::to('img/eletter/more_btn.png')}}" alt="more IA102345" />
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="desc">
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                            </div>
                          </td>
                        </tr>
                      </table>

                    </td>
                    <td class="wrapper last">

                      <table class="four columns">
                        <tr>
                          <td class="panel" style="background: url({{ URL::to('img/eletter/panel_bg.jpg') }}) no-repeat center top;padding: 0px 10px;">
                            <h6>Antique, Orlando</h6>
                            <img src="http://localhost/iadmin/public/storage/media/LewdgJF9d0j3GmV/full_0000225b_medium.jpeg" alt="IA102345" />
                            <a class="more" href="{{ URL::to('property/detail/IA102345') }}">
                                <img src="{{ URL::to('img/eletter/more_btn.png')}}" alt="more IA102345" />
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="desc">
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                            </div>
                          </td>
                        </tr>
                      </table>

                    </td>
                    <td class="wrapper last">

                      <table class="four columns">
                        <tr>
                          <td class="panel" style="background: url({{ URL::to('img/eletter/panel_bg.jpg') }}) no-repeat center top;padding: 0px 10px;">
                            <h6>Antique, Orlando</h6>
                            <img src="http://localhost/iadmin/public/storage/media/LewdgJF9d0j3GmV/full_0000225b_medium.jpeg" alt="IA102345" />
                            <a class="more" href="{{ URL::to('property/detail/IA102345') }}">
                                <img src="{{ URL::to('img/eletter/more_btn.png')}}" alt="more IA102345" />
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="desc">
                                <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae.</p>
                            </div>
                          </td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>

                <table class="container" style="background: url({{ URL::to('img/eletter/skyline.png') }}) no-repeat center top;min-height:395px;height:395px;">
                    <tr>
                        <td>
                            <table class="twelve columns">
                                <tr>
                                    <td style="padding: 10px 25px;">
                                        <p>Phasellus dictum sapien a neque luctus cursus. Pellentesque sem dolor, fringilla et pharetra vitae. consequat vel lacus. Sed iaculis pulvinar ligula, ornare fringilla ante viverra et. In hac habitasse platea dictumst. Donec vel orci mi, eu congue justo. Integer eget odio est, eget malesuada lorem. Aenean sed tellus dui, vitae viverra risus. Nullam massa sapien, pulvinar eleifend fringilla id, convallis eget nisi. Mauris a sagittis dui. Pellentesque non lacinia mi. Fusce sit amet libero sit amet erat venenatis sollicitudin vitae vel eros. Cras nunc sapien, interdum sit amet porttitor ut, congue quis urna.</p>

                                        <p style="text-align:center;">
                                            <a href="">
                                                <img src="{{ URL::to('img/eletter/request_info.png') }}" alt="more info" />
                                            </a>
                                        </p>
                                        <p style="text-align:center;"><a href="#">Terms</a> | <a href="#">Privacy</a> | <a href="#">Unsubscribe</a></p>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

              <!-- container end below -->
              </td>
            </tr>
          </table>

        </center>
            </td>
        </tr>
    </table>
</body>
</html>
