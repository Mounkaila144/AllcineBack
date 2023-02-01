<!DOCTYPE html>
<html class="no-js" lang="en">


<!-- Mirrored from invoma.vercel.app/general_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 31 Dec 2022 18:46:09 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8"/><!-- /Added by HTTrack -->
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title>General Purpose Facture-2</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
<div class="tm_container" id="id">
    <div class="tm_invoice_wrap">
        <div class="tm_invoice tm_style2" id="tm_download_section">
            <div class="tm_invoice_in">
                <div class="tm_invoice_head tm_top_head tm_mb20">
                    <div class="tm_invoice_left">
                        <div class="tm_logo"><img src="{{ asset('assets/img/logo.png') }}" style="width: 100px;height: 500px" alt="Logo"></div>
                    </div>
                    <div class="tm_invoice_right">
                        <div class="tm_grid_row tm_col_2">
                            <div style="margin-right: 90px">
                                <b class="tm_primary_color">Email</b> <br>
                                maisonturque00@gmail.com <br>
                                <b class="tm_primary_color">Contact</b> <br>
                                +227 80 36 62 64 <br>
                                Ouvert du Lundi au Samedi
                            </div>
                            <div style="margin-left: -60px">
                                <b class="tm_primary_color">Address</b> <br>
                                Francophonie sur la route de 100m à côté de la station<br>
                                 BM TRADING en face de la clinique TEMPS MODERNES.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tm_invoice_info tm_mb10">
                    <div class="tm_invoice_info_left">
                        <p class="tm_mb2"><b>Facture A:</b></p>
                        <p>
                            <b class="tm_f16 tm_primary_color">{{$factures->nom}}</b> <br>
                            {{$factures->prenom}} <br>{{$factures->adresse}} <br>
                            <br>
                        </p>
                    </div>
                    <div class="tm_invoice_info_right">
                        <div
                            class="tm_ternary_color tm_f50 tm_text_uppercase tm_text_center tm_invoice_title tm_mb15 tm_mobile_hide">
                            Facture
                        </div>
                        <div class="tm_grid_row tm_col_3 tm_invoice_info_in tm_accent_bg">
                            <div>
                                <span class="tm_white_color_60">Somme Total:</span> <br>
                                <b class="tm_f16 tm_white_color">{{$total}} CFA</b>
                            </div>
                            <div>
                                <span class="tm_white_color_60">Facture Date:</span> <br>
                                <b class="tm_f16 tm_white_color">{{Carbon\Carbon::now()->format('d/m/Y')}}</b>
                            </div>
                            <div>
                                <span class="tm_white_color_60">Facture N°:</span> <br>
                                <b class="tm_f16 tm_white_color">#0{{$factures->id}}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tm_table tm_style1">
                    <div class="tm_round_border">
                        <div class="tm_table_responsive">
                            <table>
                                <thead>
                                <tr>
                                    <th class="tm_width_1 tm_semi_bold tm_accent_color">N°</th>
                                    <th class="tm_width_5 tm_semi_bold tm_accent_color">Nom du meubles</th>
                                    <th class="tm_width_3 tm_semi_bold tm_accent_color">Prix</th>
                                    <th class="tm_width_2 tm_semi_bold tm_accent_color">Quantité</th>
                                    <th class="tm_width_7 tm_semi_bold tm_accent_color "> Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($contenue as $content)
                                    <tr>
                                        <td class="tm_width_1">{{$content->id}}</td>
                                        <td class="tm_width_2">
                                            <p class="tm_m0 tm_f16 tm_primary_color">{{$content->nom}}</p>
                                        </td>
                                        <td class="tm_width_7">{{$content->price}} CFA</td>
                                        <td class="tm_width_2">{{$content->quantity}}</td>
                                        <td class="tm_width_7 tm_text_right">{{$content->itemTotal}}CFA</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tm_invoice_footer tm_mb15 tm_m0_md">
                        <div class="tm_left_footer">

                            <p class="tm_mb2"><b class="tm_primary_color">Note importante : </b></p>
                            <p class="tm_m0">les dates de livraison ne sont pas garanties <br
                                >et le vendeur n'est pas responsable des dommages qui peuvent être causés <br>en raison de tout retard.</p>
                        </div>
                        <div class="tm_right_footer">
                            <table class="tm_mb15">
                                <tbody>
                                <tr>
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">{{$total}}
                                        CFA
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Tax</td>
                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">00 CFA
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color tm_accent_bg tm_radius_6_0_0_6">
                                        Somme Total
                                    </td>
                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right tm_white_color tm_accent_bg tm_radius_0_6_6_0">{{$total}}
                                        CFA
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tm_note tm_font_style_normal tm_text_center">
                    <hr class="tm_mb15">
                    <p class="tm_mb2"><b class="tm_primary_color">Terms & Conditions:</b></p>
                    <p class="tm_m0">Toutes les revendications liées à des erreurs de quantité ou d'expédition seront renoncées par l'acheteur, sauf si elles sont faites par écrit à l'acheteur.
                        <br>Le vendeur doit être informé par écrit des revendications dans les trente (30) jours suivant la livraison des marchandises à l'adresse indiquée.</p>
                </div><!-- .tm_note -->
            </div>
        </div>
        <div class="tm_invoice_btns tm_hide_print">
            <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
          <span class="tm_btn_icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path
                    d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                    fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240"
                                                                                                       width="256"
                                                                                                       height="208"
                                                                                                       rx="24.32"
                                                                                                       ry="24.32"
                                                                                                       fill="none"
                                                                                                       stroke="currentColor"
                                                                                                       stroke-linejoin="round"
                                                                                                       stroke-width="32"/><path
                    d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                    stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"
                                                                                             fill='currentColor'/></svg>
          </span>
                <span class="tm_btn_text">Imprimer</span>
            </a>
            <a href="javascript:window.history.back()" class="tm_invoice_btn tm_color1">
          <span class="tm_btn_icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13H20V11Z" fill="#000"/>
</svg>
          </span>
                <span class="tm_btn_text">Retour Menu</span>
            </a>

        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
<script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>

<!-- Mirrored from invoma.vercel.app/general_2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 31 Dec 2022 18:46:18 GMT -->
</html>
