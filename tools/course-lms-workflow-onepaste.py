from pathlib import Path
from datetime import datetime
import base64, bz2, hashlib, json

# COURSE LMS FIX 26 OKT 2026 - offline, tanpa unduhan
# Dijalankan dari direktori aplikasi Laravel (berisi artisan)
PAYLOAD = (
    'LRx4!F+o`-Q&}zqRB8a0uK(>!L_|e@|NsC0|Ns8~|Ns6$09=3ofDix>0AR4b@Bm5f+CJyKhq2?{0NZ_Z*@M%g>!6_P-)CNc<Y}RL*3IL->Xb+&_s^q%d*58T?)Koly6tx#dvnjc`P}Enmp}mVt@qt|'
    'ysTGqq1P&&@s6{4%GG()pmUDC0Hf<*0i{RVlYj~)wE%QCED})by8r>R0QwH>09M!l8UPLV$JG0~w|mOpUG&EKSbe+R&v$v~UA@-QTzQ`J+$r5|wmYtdJ)INYf%L~cn!=8|tv<WkYhK@c0`DR0+dbX)'
    'x6VN9uFA`MT{!QZIqLJy!F#)1#KrJF)@|F?yTC;{?>;-Z?_TrH+ef|z54^c<Upu)}b@6k%D|_I*?^4^Zvi0uu$sW2-dv~pC4K(*^?Wu>p2evuXy=JX_cW#Y`4(6IWo%g$$?8@os+{}v}VA}271deFU'
    'wuuX7vMHS?8liQqVFKC|1_1QHItpcoDHEpP4SLk{(s`}4Os#o1+e9tha_HsE+BVv2UhfI&qFZI?VKXd+tXSQ3U96y4dgr~aW^7X}3|$;v5`+ZM0%QRIA)pgRlO`fz8jL6DMn<2hHlS>#gFtGY0DweE'
    'G6^vTnJ4Ov8hRm`jD~<{000^Q07_}02?&B|qMp?}5u#%%G}|hEsreC<dM1ac00003)By@4f(VRBl+7k7>FN@Gr2S1pc&4YOqtGX!88pZlqyPW`5=2IVWC5TQ+5<vhKunDpPY|9>G)$Aynm<!hYGnOT'
    'U__8YWFQa%dW{7(O-cDgY3ZZVo|>PkVtGfRXaHyc0MG|I#gJZ)f`{(C0w}^DfC`_~L+g?#iijA57)X*}2^fNusE7zkh@@yhA}I<|ilhoD2$~W?MM_0!29aWhphB8a0w5?s3Mmn35`>|prl=ZeLLd?$'
    '8H$2t2x5w8CJ<GKqACCi5}A;wkV+^hs)&lHsG=Ao0w#qbs%cn&S{0y~6qqQ8peZS0D5h$tAyS1VA}ENYmH=s56e^a6l!!s38JHxQXlRI_Qh+E0r9^^3X`o63Qa~952?CG@k|3O)@&3H;yZ(Ql^Z%SS'
    'e=Q#0P`}KCAhIdKYz_Y%Yo}O32W>v$Fo(|{T_XR)CBh){n%v>_8_>nCz}$L`EnWG?sr^U&y&bTOUmo<;WM!`T!w!SNkiiI&k;x(Sg&lH*sfg4f*ELl?yZq7b?u}!U^8K*a3Ok$3c@%zpcKx&iG&lp9'
    'RgZr`&NqmW3lOkCl(e+53lNbBkVOVZ!}9%~b1_EoHz3e)5kGJI_K3qtW}7!R-#Kvc4q0Y4P-bR_=DQa-M)koC1XAgMg@Oi)E&yscW(#2u5dqd>^ExkW{-@v({=dI)%rxD?H_<2zTnbhm`d@yRFR-YT'
    'd((857dP{%^ikmkX#yey=l#$)T8a67XX(^_dxima{{Kw^Gu_@Y{omu)lnts{$jj_RFTZitb&4rNQO7d~sgOjPe3E?@FoeZwcK*-Phhayo(qXL1w~~Uv#8LGWNFe5i4E$u85JN<Jek1y(7-9Rp?T|AF'
    '5eI|HdK^tcB8i%rml^XHaY&X5iD;-n5hy8$@}l92f<|yLDk_2Sm`tetV6`m?nBF*$ek01$;B8)Nh>fw{l|Sk6>Qljcq|ZC#cZPj+Kf#hjld{&Y>Ps5s<Ni^^L}-U{m50267olZL3K846h8dZe1XM?S'
    'b0F>>3_3d)b|I2UWo?v_psHV9<veL7m5joKF&1c{l2IncP{}Bm^s=1H%0lMgV1sdcqDrqQ^9X%S$0oL@TR@~p-;V}|5O+`pN4@Q^Y#9=v0=jBvu`3uk?KNdh4-q=SD2+m-XEKW<EGx_v2nHgJjwLQ7'
    'Fd`w&-En>UII&~HY>PSH4)0;JS$yKu7roKu0YvQnJIA>*hpLE4ryg@0IQD+0bC1Z>*=a(f{$s%lN`(GiICWnKGhd#@`R2_p?(BE=Ss#P@wm@v4f^5D6g3x3h8K~2Uj*s^bGu<vDWc1)xU#(J?{-wbG'
    'N7BwsgXzJwyZYqg3Nit$N{Hrl)iio@9pl0DLkc@(H(UNw&Csz*6XN<vd<f5L-cH3)#Yk*YWEJryeg$xxJvv;I=ZY2zk0ElHW-N9}c!umS^$KwvWQ6)|Dz}H@?bfC3BwBBq8;=J5^axwwSlzUw2*y2u'
    '#g3YqCBMqY9sNuqAW%V=K>!s>0?=*$f(F8=td$bDAA4!xy8$ell)m1(q;_Cgf8R$c(VKACDh`_kwM|RrS*hg@ePt8BIDAAN^oGSca-yi#weGoIgTT{2cB|g05^N<!Ak*P)VxmM*OYRkIV|m$c@Cuw_'
    'z(B*V8nmLX>#M=tBjKx6YWkf0t7od|6|GnD@+<YLq!w~@7u2i>e@|deSY=Y%C;TVuzY4^$o#XDd$UrA6%7+H2^gzZ0GmPT_m!q!{5ds{*t}Zv}lZ*YItS%H3K0u+Np`f6kf}g;=yq3p3y7zCL40+@0'
    '>}kw%N%)yQE6xNJ>)P&P&#6Qw<Hr8XZd$tX9s41VXGmqmVC&bZSienbZ5qZ@kmGxwMT*FYotS7bBB;GO2p-06_3RtJ+~&@8BWT59BTS;a{)~P$G3<XxtMISxTl85B*jmuE<E4#%kRrHWZ-57Y=6;w5'
    'bT<$+;*WkU`gNOGF+UGQDasHcrJE%w$wZQlQIE4_)xwS~ef;-xR(<yHALrq{|A&=4zMBQ~CU;O=dk#IZ;r?UA*QKhw2qZm7^;fzp7YyY2qKN1Axe~Pq$yvilR7u7{_ayq(5&ay&`;A;8CoO685Ow?<'
    'd8tF%!IFp|4mfm6Pgdv;r)M9~@1<T<%ks(OKWhiBzNaL9i=BF}!Lq4@gcXvtF|BV`J#by`VsqT)y^jwrPt=;I)y$~WcT25bc_mcZ>$>@;uL%5i6f9rY(u9Z%(GCSo)Uk=~2R|0oeoMitoFVtUG=d-Y'
    '78W&t>lWQ%cKy|>-?A_N4}Su*>{g>N%_?oT*#kO+`;INB(q5`&HVr>WrbtHq{Qwv9J>_$7l)6=P?a`03EuQtJ<z1L6+x6V?5pqC<&vGcSWoWy2j^7uocCTXZESW{kMQVq|&Y;4lTKZU?s}IZ<kz!;x'
    'Vf`N~?YeJIn5551@iJ$AkhJ-<l@lmQIn=wl|2Mm&a4nVJz!*FEiloDEWA*#+-p6e#?dO?%Rs5&DiE5*VjCZ8^IE2{{BtYZk)`9ip-=Z$>$WZcacpmxpp+W=({a*qD$wAsH6Zc!bNi$5gu6?C-$8M)d'
    'R(e~fI=dpbHtcLyeZB1&N2b-b>=ts^o@Y8?(W!zl)Q-)}3}z}jpooB$zEG;=d;EO;7Wj8pA)fYkC|oK2uddu4oHy?3<>>58Cri!JuUJCa7B4NsoQkS5N_QqX?Paz#78ZJsHTvy&<N4GNFBKfP3Yn0^'
    'SSu<HX`Tyx`X>Z`we}x4!9_9zd6GDe)Hm8i$dEP7v?QWBlActbgXHvaR*|dtpAe8OPby@hT(Y*ae58-q)+kx%fw5jZWoU6?_p^4EyUtlDAAAZq?VSErt3DEzFdFtEkg6RE4K$MG`Q^uVoAA)YQ9GO8'
    'k)qwlt?x0}rMzm_wfQ(6A_$JT&Dn+C82i<ben~F-_q!i<+#b(J>i55I)wUixskf9VWM=VQTjLdkdN&!Xo=$4Ky}JATQg+>@@+Z1{j<TNbvu(btG+s*$+qTSfHEx{#Ya78jM@0kfD8yam%)>nuJV)v('
    'l`N_LWwb+E2%mcm)mn^oRg0g<5tKTW63vazP4dMWj`PVN>!Ev(9<OPAI+;85D*70{{=@G3el9*0gp88#rUI%3FcuJ4hTaV(XGizu@EG6=s8}i{J-bI|+*KHN@7Z{QMQu_N5Ppp|QU#q@yKA~_=U;5-'
    'Rt91N3+OB)`1eH^akUv)yyWZV-S@j5xKyEXsRjfrObB51o?}e-dzeJJg7m*1oyXPZ*8Vrjf$J^nDztx}Y->lc)(MP`(ePUE7=@_`InUhrTp;qjN|_~{vxOJ<yx}7<__Q_F%JOgje(s2h?BSaceUPZK'
    'Fp@DYwFVT$j@Tz!vK$y?*=N)trCGgp5z}G4S=*OwT(|9+t$u{7lsCb!!;b4<DAmgNEE1w^e|w`lY9YKmn}SkLV7{PawFEfwXCSauA;U-ATRM*=f+hNE<*#&oHgG+?o6$WY5W#R!lJBP%d>bm4;}0{b'
    '_^OL%hm5sz!4WPVTPESQ>1i$b!U|s&cbKQTnA;k*={kC5$%FE8S(F9#HLi4-`NA_m07@Qy&h+m*T<;vWiKuLDUmA-&8Ey0<Sm!qABEOt=A;-V&wiDd%M;=G%q;<@>68WZypATjFpxzRBsL$&?gb_#D'
    'Bm_nXBosj)ut2;HoYvFe;Ma~jE#lW+GO-uiIBKbm{U?M8c@)AuW=geF@=@e6#`U^#t0qgIb=SC5Cp;~C{M94h<f4kDJVxz(-LX6S&kB^5`L`;Zo{BIs$(Y8~Or4@e=ThpV60LWuDyk|D2_g}^>(Sa~'
    'f~16*K7fPE&ytp6-kZClaW(EBmMmg`K*jJ?K#FMmo8(*S>)P9POM0g5gKyynPNw!fymXVjGj7YFN5jT$d`9nId;>K^1yNKDI{QsuQPVYgPmJ>F@5^A!NEhI~xZ|_thDLF4*nBc=?2Pe408Xx$7yYKb'
    'S)C2ObAOzpQdyL<2dB+k^>{h^A%pzWBoku{m3IMAI-ZMLcqzPJ1`8dsY@KCV=Z%Y?vzeG8W$=@FH1v*gGB+FdVGm(~b(6nhlg2(RR!?&)7NwGN@O)mc!RX7Sl{kG$=TB_h>K#=EhzJL7XsA6l67<+X'
    'EGz&(fFG>T7x0ZjP?S}81CM}~4KG1_E+|_Cvq5nwf!i8e!lE;av+duNyf5!NcyW@ZV%1b{opZ;=N88J@)9<s#$ntgFjr+HDI=_XNUD@ffXNF!6&*f*USQ08vcg#dYqy9Nc15^|d4?u{}0)fYt?K5{y'
    'y|bn9_0fHC@%8t4lT2fn0G@jKF~4|%85Row5E2r)YiVPOx#=Ze8iy!czn%6*_(Ha^nkw}rV-K!PW9_Qs&3{q)1n_4a`z*7DIe30vg<tDbL6rEZ`_Eb+2mOEufdzu7P29V-Z=3e3$>+C@*nW@6AC0B|'
    'Djk8`!IXbe=(8q-#ydPT{=<n|(w1EX@23h^B}@Lnu1@TKlb^1&+x_j^u8hjYei=tHDlr2Za`M7j?a^<~zL0wLmEQQTv5H7_ttRmbeYS9qj(c;{Gi~&@JaXS#Yrb>0E?xqtJ|PuI5Fs%QBMej&Bm^xm'
    'LrXwLFocN25Lm%Mj06c4N&pH#QID}duK)@3fuI^n00Kk_1VTZPMJ@>uDhnbeC~zvEDL?h9s;a80sKJ4TL?D3(h(MK+n97xD2nvNs8W4*j0R|)yf~^QLKp;6)RaI40RaI40RaI40RaI40RaI40PytjX'
    'D<X^xF;FH_2#rC4NRbA9I7GmI?_lT_8KFP~ht>p6o}zzG%70(!ju_P@1Sl-Qh-A{35dV0<#nQ&phg^Ggz?9X3nC9ubfsa4E|5y5YJI8amhF0#SQw%UgOzfMcj2cE~sT#qhyNS$jgVDhAaE>#+zMIb$'
    'Ng*=R{Z>R#1rglgIjsc|9$?uL7?+0)O$_Bf?SfVk`gK^%1Lphy{)MQZB|$oTeI4~(-p&*=k|PpAY=O6BEmW0nPiMvA^LqYaeP1t@i2kqT^l<g#*DN`imen7!<$n>Le__~r9hid?a~sf5Z5a1dEYLMz'
    'jdtxQ`@gCD-Fo66_4yG2MLwV1e~aeq9(?Y3r4mjL&eMz2G02SwA3dh{F{K{*DH)cO+^iM%Z#TT(`elulqm0;P6>D#tB0!5*<ZaH8bL+CBwf>)OI$p3c6VgLmkXZWg6A>3lvTj@$KU1Ol^A*${5q2+>'
    'M#*EW!C@snz_d9f$iZDK6ny}&VbqTj*hwvsnnMocbDp(4Ky<usFIw3i7jlt(=R2`+sU*PyV>ws!{-Hm4`^6M^{%Q01^X<=H#q1Z;zcl$B%nyt6C&gdjt4Lx4YEx?K`d`sc;Q&wt0F;0oy?qZ~9-V$$'
    '<r&~kfqLL!kVFye7yw}_5V%W_Gy%iX59`iLK?O?^Mf;4!+%OoVfZyD1#1zmQhB#q~FwyJW-+u!BOW+&Z>Az(D9(wEV+diAmv+b(P`GvLZFE|t&%nZ!TiraEC(PHjkn2}+D2>^Y)!+YW5e)#k|cJuai'
    'xX0pW`$H2J_L>gic^*P?z~+caXu#c70XmoU30X}^7Ffu14hR(zG{g1+Eihos%aO>|2o%dwQm#Q*Fc?+e+eI3fl!&Dv`nQ)&MmcIIoMpB}C=L-lps9f(2!J|z{$9KE9!5eC3#h;$dCXZ8vAV%^oaS>k'
    'OgLe+X%K8CDaFQc)xcyG5UJo{B@9SlgbjxXa2xVmI>K`8sM?Fpq`h8U#HR@SQDz(z*`q>>9^ylg>LS$YLUbbVJ+{ZcWYWjcM4Q2HCiJy}q>YHPXnso}%G~wmxip-<`<BB)b`MQH>({nDLCF5eIw*d='
    '#<FZ6NXivwnhSkSL~5k+tYL70geZcc+ryz_r0c&~WZ(-~dDQ`OfaNN|PH`8tmtqC8XOW(KpFPfUCQiVJoF}>vg(v2LLwY;ZQHHkS1vBGauP8Kt@|8I{W0Leiw!|j{Ov#Xk3JxNQs3%$zF-I)-m|+SO'
    '6CFhrzf0;a`jgHZb=VGo@B|Eqf(h}j0Zo)Xlpe|E*YXd_Jj&k!<H+>WbXD(b0Y$e@4p`2$iKll0P@ZDpU!OJbny%A|YPnd}K@LBn)(ga4q!HFZ5zXk2Ch&`YfZiEh>&WaL@x>y%NPz+(1m`Dpw4;1?'
    'mHn|qrKh|M7^%hcXIjXsCs@Tqc-6?4_DR%WKaZSRM}J)Ur0wo<X-8SI#ZDkMQ!t1TLk*^7y&I~2<dB3R1cIHrWUQ7A-Ttgtw_6f?bF<a9k)o`IRVFi%*0s%O%&j*gl(S4nDuLa~#aIl7O57!oAfIFR'
    'a*-^D9D_FAWkm+NsZ1gmB&G`?qQ1*?8Qpk-p`*#8+=y366{p@%r{Fz^^>eH^D9F?C<>u2w%kH-K4M4qr2lIO(;*=JJ-%XWum(iHV<J_XayT?P(fAo@yzf8{sL_Mbq=(MExejir)UcZt3DubB21t~ta'
    '?TIw#h~Ue-8;t<pfO+MS^bo?f)zp2<#Y8BiFl!*YUL>I<W{~B0fec;_QtX_HQmB#=bfRIeKX=d1FTvkiUe3F{0oWjfCs$qeh9=3Qr@_oB^(rtFAdpBPK|(0Md16nJhsR$3z5L<VS!YQMRau;}R8^2h'
    'kV2lb#wmb0zl(tNJ>ROpVdagBpDZp&T&JEZ=suRco*J18l00wF59GMAe|&@11JExJ{qgaD)*nyeF0|{c1lzpUwnc;<s6gb$rqS80p+!`S0{PpMZ1t&tqJ5Z{RYwl2{9qCvlaMk~v0+f7SL_GZ1*{-7'
    '|7;7t<;jS%(P~xRU>Dek$`D_2fch`KrEb#`_n+jwBsQL@EcYGv@;T;YuMNJwSJB})cN?b1>wv(31P~zvg9TUtywZ7g{{Kt%^gCwYzJbBQ;}H~)U_p>eh6V%>1k{j0gM*ODKte$c6i`uogb3VCcdbj1'
    '!rtiNQ}Wm7zir@%nl$Snb#?54bH_*gFTzyPjQk<74UF|uV_Rk)j{6-jM(cm{{2TfGuVn3b-_bc0T9to9tvl6==-kbU!#P+}eC8~T)#_#O&FM56x|_siYcpCN^=@vY&G33TnyoD{4sE%y%r!96(rY-K'
    '+eb4RF>e|=s}!uUQoN%n5z~`7M7&D0JF-+gBox&Xg%t)+btxfb!<^FvLlmL_n5r}UgQlgSN#`}zwFULK9Kq4T(DYh|MHobqOh&D9%wfx&CUplhj&8<fH9m=k^U2XY5MEpr%Y$}si8-66tgSoh0wCU&'
    '=H6UIm?A<bHgbBhOpyrHs;FG8Tv;gGXptI20fh)9k|N43L?aN56&izis@>Fy7>Pncm`hP<ZkCWpj3=x!ezGEZgeYABnl;SrmDybz$xAzLoEHsVM(;#xMiIT63?o{dJLV4JFvUBa8`V}QM&e#`OJ$^1'
    'tk`i}@HFI&nYD68D&y0o8!qpgBN2t0w`Iw#7sofl3fVftS*tY~HKj4k;e^(?!Ax0FxE$Rai!EwY6ju&f(Cp;h(T3FM(8gtJdoNa*H(EDZdsw-?PVrQcDAXSo*`hbDDqA<vg`1Z&_gxz<V9~i`Ro;NY'
    'irwWEmJQ8JHLHfRCTiv|snC83-t#H+RX80~PbF<+W<v&c+1oJ#x!)8l<?__+C7at?+@)IwM)#sJOInGhx|qk1C?U{LJ_$)sB<dds>QW;~VKL}E<O_>%&_q2&KI$rYWYC4Z0KoG(DzT2@>IOF`B!qpE'
    'q0Egd>+S-9!XX%1sJ@kDRa8`!6}b0iY*6stgUhqT!IKvyp<&&cIDMHuEX77#xIBLq@{uv8BtK6mbdzMFlu8IDaw0P<Gf}Evzl8hZ25Xg6f2ZV_`^1nWhu()1^PPg5&a(S^{Z0E;+R#x}FAkeMQ<w*x'
    'hqSBz=NW5j;KtplV@KSnu@_xj7D~o}^P63KWB|56VHM!0!@z@xWObR6u?sjNIzu)-LM%N4+e|m}!Ktv>?|ui~9_QS6==SrCBk4Yz;vTWX<(U=PEWk4Z+r7h)nRDz8Vct>h)xn|=)|+(z3fzU#7=;wZ'
    'I%X#jDyc@!kAKTxS&_IDY?)4B#GgS$6;tRq9>VDY*RAo9={*ERzSL-EXeNk7OPiNAdgC?7@a#JKJO}ioJlXtZ1SJJgK@WfQ+rQV3+3bl#EfWDC8DE*sL|m}F%n>LIIpxso5&hUbzs3IlzjN2yJsrad'
    '?_p7H5sn^-c{Azh(ePfMN%zc3GOV!T9sKYNB~ApJbe|3MGl*eblW%@3U8j6wI|&)F3Jt<Ubu`IFL+0L$Sr|xo_1%}hWTw%>)O;TQK6_rqaLo<cF~VHTf1eHrfi)9L5K-z}InEraU(a+$u3tgoj4f1$'
    'H<RuMLqNcLLdos}$C6=`kH-LA^uSSsUgr^!77{Xox71BTl6ru>8f*)^DE#IK{3np(5=Jt@V9f#M90?Br!x<&T$qR!L#)tY8)h{|~*fdBS!R`R=IUEXP<c|h)jv|gwOw-H>xu|9`0P;-}KpWXK4g%rA'
    'T*w^qUIoKzWj;-u8W&*-HymU@GhG=B-lSO>W===C1;_x`kmUkySQ=@JW>ILDKw^~+p)Z68fujz?VCo~_6onN4AT@>wXlsiCT2w5ilT3%t0-=Z%2_fZT0!)Zj5+X!VDv*gs#*~Rb%n5<s#6Zv+2E~;C'
    '%IYT|v7hbGyM|PPJ1{E<1?z9&>SqSQN)iPv9274MuNKhm2xRII$cFSx1v-LiXa??s!`@}_Q4qQ8qt#zwnicqYe0?})yD6@J4VdL3e!3NUtJ^4lukYn`Rz&&Q)2VOkd)@Lwo$#$wA%C?;sRp%3@lpc+'
    'HFcCOF<IU(>e~|L^a(lr-EeA}B=^Ul>>i<+a7hCC!HlCu1ezn^B$)Y1Jx2C3<Q!mVqC`ovqeYC&M?w*@jh`<8qec`OI7!$+u?IpVtT0DGu@ryAlVohLu&8-zO^FY#o|N0?yVT_4zr7T3&0f7r<q5Yg'
    'g&`;`_?2q~qV+wzsvQMO8INPeqLOBC>sIMEgYIzSzY0pKT+{Mrln+3vEWj*A6FjOJo7S$zFf1Fd16l<{kqAV?JGX2Hd5t*fW05m^+Y;~@9br3b94O%jmPKD)3Fr1~Nl*b*RcAkj53dP4&qUeEK!h<8'
    'A>5t&(Cq>df+R!9HPJAty#v2a9fTzdlQM)sBDn&7@#la8u7YXHuG3lp_;)g6jWVBk21FkSc;9R{cEAs(%fG4n0exS$<cG8{*RY<xzg3?A@N_S%;?^=sqq1i6Z7p8fId#q?RU9k<q9G7nE2+x{@<}@#'
    'JFWp!A{0o<4Ml?WEX517xG1rRN0||@FX<qS?Q1+>1}>beZDN3moA*#_A1-v6@fcJ!v5k7yUyILAn&md@+`Xz*Yx*m;wh<BNI`rXQ^v)A4v0qw|&|qO1$okBV|HyB#Sp?s1IV7nJbx{I0zL2ov5#XE|'
    '+plA(B(%3Oh|`}f>Y?DoL|qZJ8d_aFHB|ky2CvcFsqGxD*mpLPt*uqTQeI(|H5zbqhr&nSB{EW&34{~Xqf}Asfg*$;pO_!q47@4X*)f&x;^vU^9bO&)`94y#Ja5ce?FD`IS$jE|@()n^KxjkhJ?D7B'
    '1&Bk;P)JxrF(K4gzyg6olENQ{lQ@<f!9X+&6cPf387R;wqf+Dh5iAEHB86BkSOSb00lAZWft>#;e>fg}pJ)JMt2izI86O3M_FDtK<LPWJ|C@sYPS@0tF9~w00Ljciu4E<NYPF>n5pT<!RqzaQh5$aD'
    '44`Oi3uj^{<?^DXqFSg<Kw^#nZADn3JcFiq5Wu2s(;O(>|Ab7KB8}ca0o((Fd(bw6ZYYHkiZ1BD7Qw*)diB%;kcVEEI_a<1LY%(Gf+*xH7a*CRWpD$G9@wBz1}Gv3gouQZ1MdBbMfH<`EF>Sz{73VC'
    '=&TDlxwxB|ob~&Eu=oAB*zE3Mc@Np!Hj;~;T7NN*QIsR?Rr+V|tmabcZRhBQ^IBDzOS0VUWo^OD<HsD)xspxUm(Bi#!t-ZERl+Z3>|xhR#`v?H&4477K+~Px>u-No;xrnQfKBb<^%grTL5WO0Evi=X'
    '>^A1!scGP+S0v-tbo~<pqKhDjQ3h9}f)oaXwEkX$9~U9?H$*a;^sZ>q1;_wMMHkBqR)1!{j_XQx$*up3dyu4JJ3dW=T~;;w$HqgkxaTv$fB^>O?}G5PquA-R{n)O9Eo!K@c)qujpg#d0OhG{lAFlm<'
    'sNT2hgf=;RJ6k?B?{M0(#vpelI-OX(a{Gm}Y;digERH(rt`F2tD0QQ^Cs!~*-7~GA#k<aD30;zv`~HaX*})s=t?JtIxV?<>oR1T`1IMIeaeNeT&iB3jJMeZj8E$B7MdnEEn$XV_)0yjp@I~nHH+U^$'
    'ovdB6gXSVvyqhrAdX?Grc5#GIsd!Es+ULDOuY|UEC4Jbl#Gd?0kEmw8zX7Nj>#72#;4<y{Qj%t$h;FPXSV%;E_K+Xr@#ns^y}D6kr@D<ztseA^Qk^9_ioi=pEn($*wcj=GdU_URYv+4Ioj&oiw)YLG'
    'ObDGGq7;TC+PQ7zt*I;V%sJ?2ZA`efOA%fYW}zmPkBy51<6hez3Zr3Z3?Q&ls;)@c8rEDTv_nw$qnLsKqM-VZq0yH}X(?@=N(NmY8I*n_GAeBx(2M)rt3<rLZRj+osxYuNw6owjXJw@?L9I0r!Z;JK'
    '?&-s0#Zhuh1vU&=;S-F>Lo)Sj8#koT{sf7U2%NDoe$EjB$iWd=*7gXIi_4?V8@QYyIlN?PyJeg%l(=YQsj#!2b*ih;V2Fm>$52&y16wMx+v=!-#bDg^5Zk^k5fqVhWRIRC9`T{N@_K8j99p$?6EOp3'
    'R~uNoV*;HHgo;A#ch`KSvN$|^_R}k)5mchlOzVI-OdE#{4Wp)Qw#v1QwwvF(OlD&PBOn_P#1w<=W<>>_%^vX?gl$=eUg2D4J(Y%4*Ec&jzdZ1@64A0usY^mem^DErOP!LUT=5%MU_5)HqsNt%sPPLn'
    'D(Y!gHf*RrhK)OPzS<8Ii1p4{z9N&=*I>w*V<blqq+X9!i;Sp+hIF!5x>*CHFl{or<lCE7dRsx|iv2d`=dN&e*PzHMD5%*u9MhHs4gwa4n{>kLFv83T?S;_3c-l5Pw_(&@2%P^k2G7TOp{(Dlvak!+'
    '&(8i#+%xP<gAa4(mF;wQMI!d11hqquTV{IR4<=P!4|)$|7_IJg%>{9j4K%FTJoBc6F57-`s|n#d!Jxu1dL^2e-UZfiLIHW;FtMHdrn5T9Y}Rsot_1V}kAc!%9B{Q?7o0<f3!qN%Vw1Q`_Lyy+H$O*h'
    'u#-X;B|aYOXRP@rNAY;S)ud4`r1(97mTk)CD7k3TS!KCQG9y>j_>s;Y>TwT>nCBI)aMFpUYvD46rp0v^Rkmu_!*E<jGiHZMf1v66#Utw%(qC9t-OoKtP0n?FFVOG}cyzZc2Z)D=<@ed5LL?SoL6A}n'
    'T{798n#+av7(9#I>oI%PRn^7JxwJ?5HtXK1tk$Jga<q+T3Ve-*8{`;dVUdH_VZ(;a;x@!xyWD2_1LgFE9`HzmWcC=R2$#Q#sun9_a@IYgG!-_aptbdc&6O-$Jeud5>3-d3bSGheB3}}9UB%O@HMm^Y'
    '0T@!JvPWx&@3$F;2t-lheTs5rzXy0Qq6>Eo6*LzGuz(qc5wl_h2yDjWnp_p!CZ`V2`c<Lyq29K74SH$@kPKg7n6=!hf&{{<?YsE067CbjHL@Vuy%&}$(Q+hy5I}(ixY2i_5Hy3i0l`}9R-T|1HWCyn'
    '1L1=lF)49ks?RERi!whr`+La;5?KQW_-Zh3ycXR>j2~VYoEnGc!l<{)+JzyJo#xs1gk5`Y5_Vs3#yeQoP*PqCbUx5K9=rLREqg>By>6Ik`*j}smlnB$<~#Gh5xmkM(RG`EFde$yO@Q$FF~b1IhJ5FG'
    '-nq2UMZ~}MK0Ro91SQ7DS+)bI`Rz?dmZlw(bU?bzm?&ORBNsczt`R`vWh8sC^f!?qdod8f<D*|?yD&d22D9Ssxo-D;Q+-)wY|f-L6F^GH3wo?G5ZD>tUH0fSwmV|V=kL4uv5#w+&M`R0wbt`cyNgD?'
    '*S{8dvm3OM>GqKGWwLWbT5BF*r(`VBmBt#*LN_K}`GaOV+UFz`Z%C?>TRZTjlU%5Ka!HvU*hdYE^GMvq1R*RPZP&W^V9Ofx2xQtKDWfncppIljOKsVEuv+fq40K1J_?c$?VOC@sE_?_%Ua*almP#oy'
    'a}3!~o}89!lLRQSn>JBGh~mY%%t(_Z0weMgP>n?IFftghQBI}{;PY^x#bW2Lr%iMR&Geio7{Rb2^2R`gyg;7oZ@hfuw%4Qcl<{5LmtFN)X`tkt9^#X5<f9FnWd*+svRK<_<00O)dSH*;y`}o^qZ|`l'
    'A35i(Yu?5B_<s1Hy00lCe%=s<#$0nIVlJNB-nGhI_3y3w2*#XVi=C@y2i|2ovU3O?gkAnICqTTS<!08qM~8Iry=!QGvRvy<4);XcqAe-uC4s+6-K#I4QTT@5+V7aedg#2U-MZ_4j2AvRt52_Z^`mcr'
    '@td!e$$fgfuZaG``@WkFiM(;0G6uTo29wi5=925$N^jAiGmk}+?6pX7_^2ci0<OAD;jfp-ORA3OHM8r>QOhd@;rp!m6NDx+7#SU7*Kynu(H@#cL<gkTFWWA&okp+g#-u%TEDS){!ymr;s+shgTW3Xp'
    'T1>EUc=R#N*zE12OjjKA#U9c1yGavRq$CKEaxi!Ij0s;}!M7jE_~iR{BWLhgl%eR#-B_qp$CipIRrq9@#THk3&Y<=-Y*&_hSDKs4DqOTyhPgholytGkZEHB@C}^Hj1+2s08a^Z7wIw%l#A@VIgwE?K'
    '+RAS4l4%-DIo+3wm+rg+u6?iw0sw^vA+bRNI4kkWWvKx;V>}v5n^jaqY7|vkI`Z?EwLa5}m8+=cbo9+@o7p(B9C5=i%^usgbOl&-u2T(m4ucN|neO!PDDn^?2b+|wTCxJ%-+?J1&qhtU)w6zE=YU~$'
    'x;#j4jzn#ie_T+K9=hK*t76|c^iy&<htD`L)DoG7K5Dk+v*Dm!dbtAED*WhAN3r0WT`k^Y7vu1w*SQ$e1N(=ksw(06_3N))Ywi`l7N6hC_(#3_K70C*e6J6#;dM=>IWK8LxNnF__}DzaSe$j-@RMie'
    'B4=9PLmJdh<F?3?6W2~RwQHX;n|*VBf?QUCj5%83_tu`=h(l~iA}wfF+(0cMO_92yl)|f{z(Y(aUTqqzjB1PaUe+M?a;a)mx#+qiNY^@C*;|N)HA5&W;$@7NFc&vr=oudUb=7Cqr{R3_)p?!8jh`6c'
    '_19g;s}l*Z-)c3X*Pd`)QWzQMko~u&cPBq?^)@b6!lpk39sGe+5mgPUWLQfv5ripCAGEsULjxM3w4`<;`2Q~#Hob5F0E|vu3g0VjOUJHjx5fZ|?AJ?Vu4HuVuX~7qcWWU$qKV-bYUB@S*46a6t+r-#'
    'PqxKHyDa8L8YOpBre;wVK50^40aqC%V4~N>NeLh^Z48av+)3*3(kf((_^HV!m#xa%pmYw@_z1<c8$b(1ZNGgRYu<G3-^<&n+p6(=u+wBgn@`WKsw%o^RSG^<40B}%D)f-BGlu#iqBRlL;d3mqW0-Xs'
    's@?G4av7U7JM{>iSp$VLEEOWX++C98JZELkbSJ!i%y#Ftr@u-@<~)4FeJODapFgE(K04}nW%Aj?aK^26(E4|t1x^5;3SM02)I2ibm*Y)WcHLXIZXGx>(LtLo0N62plH5VN-RxM~<Ap4hi&9u(+#|Nz'
    'Gu3Qgp@mydxym(aa1JQASsd=8JlLm2)nAvc#|?02mFx$#Dr#0Qk|O;h&HK&w<~+xO7Kk-lI%5QWE|0rsto6}@+4vlK_2xQIFjmeQr0l9vX|uCb>iu<|{=Nvo00?**2|V=<sh8~4<IL#$&v@{gY_e>f'
    '71H}6Foa%nsP(QXipW0CAj-Wqhfdc+v!gU7Y%_ovji;Gzcg}J3w~^z_8XK>9=QH0*6Tri0;`e_$+~3Tr)?E@{V|cza37Zlr<3Ywi`FCV9?Noe)Htn}dbViQeU7e6l&Z~>ZH`=6>a>Z5YOyxmlis$eZ'
    'lri4;##<Li4X=sbH){)q;K-4dyezrj7t{5zONT8Ua*?~_Z9Q`Cuf7LHzVwIFmXY|0tL&z_>h^uJrfWIL-0!T<V?t#iKqT}OdLRO)L#7}B7hM9JnvHgs+b%v4b`6U~E8RV28A@`mOOBl_8Z5#jlZ29O'
    '+?94wG$fOCiy~4Y={R~4Rvc&%Al2ShhYSM{y^M>;okEw1?IKhU2*P-?pjtn%5>iqSS7z*7zAAk|K9+*?e=L$&ii41FpDXpzOv*Y^zti2b^lzUV4^45uqb7`|NCSFUO&<?~4UXLY!O%eMz*UJN6~lez'
    'a&S|<+8qUw+0FCJG=s5><EGC#5<z}9&2r7>XcLXw?UP+X@Uttf9k{ex^^@~bpJ!cX)^F>7V8@<az30>8!8tDa4Y5^^LOs2cyyh!?_3OUlEr5LQtd~Q5d)>=xwphNKn>Q`@jnUX_hWZx~uC=Pj;bgdC'
    'woziYXR(-~>_3LxkQVQwz+4O=^LEy)<?TK_+cj=s{N^i+HPZ|F3jZ<I&!*8AL_{Mq2Qmt3%onNnZ_j=EXVc}^JN4(`&rgrn@#^i!V}d*vGOu24q>WQmVyzQ3WJ4)-1E++&;xLsvQd3FVCh{&v$=$~Z'
    'Ce2q%KK0$+T2q?5rNd1rx#M*Bu5z~-DGYbA$Xl#FHd6UYb8(V3+a3D>2zWr?#Hi?0cjV*Eb2k+t*2xzZ44Bq%$U<FMp`6jxLaRYDH>y+Vso_F6Pi`V7u4`F1Wd}$m@IgmnY{B(4sH)#1qj}RVf->>k'
    '91M%(QHz}6N}GgDD_~0{EH!zCZBpkJ)!!|DhEG`e-J(lFq}Fd?Z;j9@s`CuvPc=LpXbf?BmM2I{VW{UdfndB#kjFXK5oqzyzM_eBd_>(Fmp_o!18n(`%z?oid+ehYo>u3Nf1N}U*?ZV7lcO5%f}2^j'
    'NWGiQLQQ=JbETe)T)}!?v@&263v{^j=gt<{O4j2xqIOgDj=HtvoW0HU$m8#V#bKcGe(NjR#*ixGn%9?3At|aOYhz&5rxS83S<SOCzG8M}f*de_{zqeBj1r6x?zZ@~hO64>?ws$6dCoAAX2EsY0ip0U'
    '(HLRS_B!e<=a<)>Cw9|Wk9;?J&|*Ze$y5Ut^5oXJOks`ZQs<y)tG0aEUu#Ez^!d82LaJd3SYe!2GmRBmNhsl!?2(0d)-L3wi|Q|Y>mz%3lT8td!Ri*&UE8yXSfoZbnh9E=u|zRkv$Cq0Yi&80-5X{I'
    '``4n3yau@|?mlX37BFDNgob8jsY03-9du7j(b1K?Mhp&<G9v0B%tPoZj9c<(DPR~FoS3ZTaE1e!YF6Y|haMMh;ftuWR&9zKm5wexUu3PW>lfw#;1Bi>)4hhW!#(aXb5EX+Nlw2@6UiRaD{|;xt6clm'
    '^wZus=DDp?HT&ISyE9NjLc$*U+4b4)TE!yI6;}2I@dv8egX>^>t#|9bbll#eUz9z4!)b@@>o@6b?>YBdrC0UP^a#523zy3Nm_&Zhi=vgsZ5NbSz*ZEVdCuhxwJ>IN$k)F<?!MrGbW_hm&pb3z*RN5w'
    'R<AttJ$2OLH$8fud`1lqIxwdcr+nJbk26u+eM!xb>cGZ1-Zc2md1#Jq#7Ey@IM=z!O}PdwxvpyKh{^fPS)+~5fzbDMn?q*w{;TdDbYtx3QUvR+;@$jYHeKlPIp3>aB>D8z<*Zg2UZyT-Zts+)TC`H*'
    'UYC!^r`62v*=W~10}lc(ZKLV~JT}ocpq<}Bi<rN5=Hlk>Y%CQjXz@11YBz%JY_R-H9UbEvteYnub;)<s@hxS=b<FiKoLX~*(#`ZKZj_6qa!a+ho!4UFi~v0AtZACo_TsU>fSgn~s80bvg>+J5MK0xm'
    ';bMv?l1VgaZ9Qw0aP1B^M_nA@&fT;}7L7QOL=;k(gcAr+GK(Q-L=A!bBkCP?2yXN@lgQ9x3zR@c$Y^Maszo?2561L~Ume~sete@o%-Vi1%*B<>qddC&?gDweXH#y(t^{j3K*9ZK$C%Lt#jL=xcXRUF'
    '-;P*48;uLp?2y${o4+9q-I~MX@Z?S0Ya^Znrz{>kryAdiWu|J5$3dRf-q`Gsm96zJ;uN>U{auSbeL8c`2CKTCb$@%vn|DPH!v*DqE^1{=^KX^|4XfdA@rI1$&15N|{4vuNkliuxp*wkjYkDbW?aDdc'
    'z4vGLnVeJ`TNCfso%Gbc7|NHRW<C1i9Kt7bTw(036jtxlmjZSqb+R3GL!tatkAD8ZUW)wnw4XqpI7q8EG#@KhmAhY8_c`rg_L-=hN>;E$*0->S4UInW)j&)xX%V@4;M6wZHKsScu8b<-PVCqiQHitQ'
    's40SpL>Oq!uw_RUwedn@2AAPb#m=JVS|#PPV(H^K7lqfxh4I)!-b_$Qu*vZKj!&WDu@8IPIY^9qzYi2b-YiodsY6G%{qnWKO3AOEB7+D5YJgC+_QG@D@@A;&89jwPr{iH=LLiz&?$CK0TagL3mbLls'
    '-=?340H4YVr{ZnJ_n|qdn2ITLpXbxG^os!CItigV{@W?x+uNnIu?RwlxP6*vK^<>N^?fiX>~;tv?#w)y@Y;GOjOs1K&_!U7QGf^=31I|*0st66Ac7bnARt&0A`yuqMTr8M0Zim&_^%5)+v4uCSA_(_'
    'nC(R7^g{_CkqAYEH~@k5!9xr2*V-+Dv*BR<fDhb|TBM*_3Modag$kyG!Ek^@XF+)R!&AU`%o4e-w7P+Z>Mqq7L4g;zu_%mXg8z~@34)&STmh;V?sOEaBh~KHsJJ8b*cdR4SHh2+a!^LJ1%*)Hma7im'
    '=p|6JrFhICVpxcxB8e)8sRNR|?yD_Ob?4WzXV`?sA(d!q&`JucC~^`FFGajARo7@Go>Zmr`1fr3KfjLeoAYVcw{z20^W&extoD1C;#1@CZs(TPs#*HW@8KIvbgTB(Bb-q_m9^DMa)v8Xt)?{mao&r>'
    '5ygU-Y)e8xsbRJ$PY5C^>}dm+{syQ>*7o~%`8(A5%T8CN(sTG&tsehr|6UL0@b38eyz-AM*4eMWs?#NCI2+lSSQlyz$jtI3f$-Mc5zzaNI!=nXmHZNV->tOa`(ry}qE_)cgU$emfe!*`@A3EZdzD>>'
    'H(b5{qT6iq^UVEUs@>#YRoUE^u7R@+jv8$KjL)GRzI9Ix4D40ShZ`B&)xD5;D%0bFTk6cMUSOdtLvF-y6(C7k5mZ)4Ia#rE6a6iw!G38}`jg;SDs8f3_lr2Zf?_5j%*^P6*S#ZKh8WHshi<P_wBmwk'
    'jsorr2(sY1V{}S#kdU=f<24kZ_AhPNZyF)N5*(=C&(vZWrrx1s%2HNNK{y9N(i{#4!uQ-gL*e7ab%%rZ_}f++EuRQInmQBVAL<&!gct-P0wBayCLx3>l4J@(h$bi?hy?mB0se4`L@I=-(Lf)cfG0Z;'
    'J%@{9))cTK*ih?)VKAnWip>YS0_P<kWmIZv1DMiDB&xVxwXG$fur*WXHpwa|L|%%-o2^>4s;y3lfVx9~K{*o?LvsY=z!pm~1#1(QhgCJ0h=2y}F}jorV%bU!P2B;pk2g5*O$|*maZ0H-1Lw9j%TYs{'
    ')kIi8(@mhLeWk)%o~%`h08ifFcBb4~9L;Ctc{#r>hu#^O(BHSeHqNl>=QC)lmS;KJE$gY27FgAB%A1_ji+nzx)V*uy2dXhj$t_6uSqCWOkK1j+`sCS(N(BNE-+V90PUif@@!x!@5-olH-Je+OU492g'
    '!{gUk=e-^ZD5j3RuX`gW+TW+1d_8x^*?@Y*S*F*HP2b@5)3p>s)K`U2NhWv2HkQfhX3ikSv;2JeSaX2P4e8OMs@ktvDg!F*>shI!b<+IbRz7ZZ`ZPBwO;w)vdXuKlmsgz9$<n%#RxF>VSAPWC?ERG~'
    'w=0=8tp!7u<Pu@h0E-@J$usG%q3*-!s*+|3rEl8}UOM6yGYS?Hh6~-}?wrCWS;fT*0;%N${0Q;%Kdf)oZ{B`T@L>~#h6kaB5n$4N1UO5W7!*TGpal~nk5}>k;|P6ckc)4R2miDE-{JMo#H`2pzw}J-'
    '2!1|L<PF|2{(ld%5wK}j?<@meAiYEm`Xo9!Dv1x8lH^-K%poz7B#6ORIoMjH!O$b+2LL}1F&`9y{U>5P0pR2me7|t`JFmZDJ7IQA_b*pL1N{FJsAKz3Dl^WlgN?U$tO-5uOn*rbl-eG@pVn+4_aBKy'
    '-to^2jj~TM4&B=~3)F%zhaiQlX8vo7LKE0{?V-8M$-le!Nm|gh5({B4Tm^*DAuw1?vv=Ebe}3xcH?2717`YUHuu3)*6F>*l9LS_L3+zc`aAkx;@+=ER;unYGzENA|NLT~W?4At5@|bKjz<x1h=R;u^'
    'qo7L#raG!N5R^Y&0@aDd$o^nP-dGrtWO&w%9D#<RNO%K2TP~ZZpoH7F4qg<35E@uw7@8pn2}o(0BqWj{Ar1#e?duNy)OFd;8kJuA3GhVbEL=QmD9&eJLH(Yzko|v|>mT;^6~<-{NGb5${Jq6ds7qSV'
    'zwF{-W`=$Pk;<QEi;4Iok4SPm)0ryli@>4ist9#WYY{#pfIfXc5l)BkEfT~N5GPtFz#_Z>L}0KzmX9@AYJ5PDdOTiv6-j<3#8O|uGK4b!POQAMdWsPBGE|fy)<tMrc7}+83!SH;OY;%f^A8^1yW5+;'
    '>HWliBiUUe-R2Js%=^KMySq`rjvp;;A-jdpC?;hGS?lucDKw#HEw7K#a-PEf?&i#W4q)Nxk6lphDd~lSORQlVoy8vLSbf(7OBkXecIM@+{pqanKkB<ZbkxV?e_8s^+8y4bx3Og}jI%Qze}mSXuEr@H'
    'u>R)dO;7&*wG|P3%E0aWuNFb{9Qk#cEI$RrTiKH{(xRw$@i>Q~zv>ABG8x|FGqn@kLZGBeb_IayOosR#7C43?b#3?2vBb$}ix@BhZ`w)|l?ws#8=hmJQ^Fp@Bh?^2$SjDtJXbXcgpRqu3%30o94Xx?'
    'L&JfbKMht7hO;+P_7%;#C4vy#;06v>N^gO;_^$L+Z;;GDr}Wws9%$n)C#dx4{o3#6{|B8r3w$D)$9q=0aFC<Sil>kYPp4@K>r8k;Z=O0HWQK2ZT^rMQ2c=t@K2F6x$K><y(r8L6^jDwBj9WOd3)LLR'
    'htJUJ+1eg|4D<T@gnr(5HwUAAY?EXyk8;l)8vcd!(NJHX<M!sqtbF}{Ws+}~<UN#ll1|j-R|X3Ov1cEjq`?^`MtZS4r!<qFKQ4}ACtuZ;+QmdszONAAhS2trHFu8TPyEo(lP`i3M&1e{c|=15f_Ye_'
    '>Mt)KcG%N0A>lFoMsHDvsEBIMrqpVuO{?BqLRTRP?L6jfl@HD4aNMBxk8f_ZhuUbSs0@Ot?oVVl5BBsT9lOl$shBR?=~cziN2;SYeoT+?^{VkYgs!9(&I;r9{GlF&gj7qJi<C=_NFygEJN@SXe29y^'
    'grc6bJcyv_-&?#j4M5a?kPg06r|mz`>FEJPFkXl5H|oCF{8c@Rh=JU3G+2(rhe1c3Jf0j61bYAWRen1stbA~T{As@C8Lk+KMq@o9ca#2afEszAOT~-|h{%B=1|Wq+R^xgbKpB|P5G^RW8>6X`QjS36'
    '3zW43gaJa>ID?Xuxg<d3t%HRKWlUy7xds(65f)*R9nYJ75$FTp%ldl=x_@kj7(xJCghoaY^3-|jNvWU^FnBg$v|I%YOg_);4@YlY4|ndu82<Augw31&SRPF0^GE#*zEE=ewEZGpuWTgly=$d#Qbd5^'
    '{NZajl3-&|>%ZCl8JddFz8~6uulsq^Z!5E(j3PBUt|e6{(ZlZ}(177-I!5=rRh3=_@l>BsEMX9P(p3kshXp9^XI+So?;Re8N2j*->qT+*<5P;i1<Pki&gb3<NmJ7X!o;ZB56*qb`N3oW?r~y0Ai^>%'
    'Fo=lBu*kw97>Xhj3=_~7*XQkF{i8A6zzsCVST@=)!)UhBbd4yf3WB#B0TkcF6etz3aVbrHPcQXrqAoN7w%0(xX@7^mOR_)J9lY&iypaB{>Vri>3k7BU@CN^SrFkD*p&t1q$tk<FPBw533V@=3!U+J2'
    'B$5P*kb<4<yfYcc#nZO{9wUU<3JP&QfIlayMxjN^b3M39KPfdquq>sG3H+-#NgThcebDvd)=IvPYzqC1wQO3Vuy=c5Tn=iCPfm0nI~xTY_qcY))a>);O-zgmuDS`93u`7MC!+OdQJP(A3RhfKvnZ&W'
    '`cD$Qx{k;5y(0>V;cgvoO}0S|>#>-p-GW+~szEjvfwAU_SPt;pv$@YA_}97^M>`9Syrew7ERMGKV}0<8BG=&&+FSv`jQp-O9Vhyz7?jRLIyY{GSRNA6mxqS@NGcQr{yhE+YCZ&<-zr@x5yhUbX2dIN'
    'YsXqS^|Wp5rdsyiyI8^0=~>;qp17$5$FA7Ii+4TFeVS=>A*qY4aW|c=5(jX@3{Z6{c?Ll8gH!~1sZWec6?#VGOM=B{mJ0&W@`eB)QEVa<;9RA!0TS?4m;*!cgK;QF0Vo!*ETsxq761jHh)@9v%`MVo'
    'C@ffT3mYKDL@WweQh=rjKnw&V!iH$;qQ)9v4uG)>05B{Rq4cPt{XlYbcEY+W0qN7CDz^~9Hu^vsXch->Bv7&>GA#p(K;Qxxpa(97ry??8644OTR4lHI^9;@r&464j-8~=l`St9!bN=!l_O4X*7en?o'
    'Od$1n|8yv$f;+%2!M1^>YhgxUQbVMlZKoR`FcHBsE&<;%3zG2N(uW+FgGv(=d45CcA|Nl$c^9{F!69%6%hT;J@ih`2oWn>qNeGlc+vVQKiW+l&m_NU8Y!hp(G^?N=0qtJypPDdS$b>RL4~lR!C{4rK'
    '(D`Oj+@}+vi*x<F;}_KQ0nxywLk}bY5QIPx0APHh8`uxwKZN=ZSnxsb^{V>3-y`m3oBfS8`XxG@7}r=-QmX_)0Q^b#Uv7pQ83FxozvuTJ0QyQ(B{$-@g+Rn?FT5y75DmJ9{7qTsY5DzM*G5hzDxRYu'
    'WMq2y11*XPtb`#5LJ)!>00;`C1&A<P4nGOXd~hA`woc<gq3wZy?|||3>(%wnc8lkPMG-NEat~LKbs%m2?)*Id{8ZYSZhzVm2x{z*SqUEFj(9?Xr4f4L)jq-<#=BnO&KQwBNN@PPp7pS?ob>2**|^ko'
    '9h4jJ9*ddJINDwcJEpn%_;w!nTf<zxqfG+Ry85qaauYisoV1KOB&13p*iSAU4#eNbi-_j!xLR>Q+D*!dyV9Y*6c<TtRJUYyAuV%ky0>epa8*{wN(*v}Z5?6j@y~GpI}cnb5XfqgHlmfX;v7ZP(F%M#'
    '8~L`$7nLGOQ;u_U;bVVyHc&6W!BuXzR=KJ{EGyX0n<&_$)l^ORrt7)r;Fu%=!qB$wg{O;NvxY=BN(3>P(agD30Ckp?bgwm(sIZS=#)Vqil(>Z6gAi>V?+*`GmG0qtcY}s_E^=Hc+rn&_fZ@PX9en$4'
    'Vi8)4u6KiKX!`w8`TXK)BZHSzNF9jplF~Y<?chCDtl%*&=|Q_1jJ7O8mndA_h`#^s{eN5e(fz+1*!-Y_-b=N44f&l)p_H7C8QKIqL#e`1!5Mk2Ih!_(2;TR3xx*2m6t&EqI<o2BxXd+jS~HiGy;kfn'
    'yQx$!OVM8)kom8n#;Z=M{~C1}gvDi4RaH8Uvl-n*-Oa59lD~f*UNcKsEuCeG(Uwb!$H*$q=$yGjJGZG<Wckr_3-ao5-H91>R5D%AiE}baNUaOD9YExBj9A!#36teqcT`EGu}l{_ozAM8QXU(3W2vd|'
    'lXIz^nN?!57V7gYMcla&mS*PmO=?c>r(IeQeWiPn+?VaK(zS-oSieczLiI)H7!NAd7-8$-h>zB0Z_+689P}m+r>A{xucv&Cj_1G+ZW{&k3%o2gkO&7btHLgSWMd^%k%%4Q!&s}hs1gCHmeF_MDJ9q8'
    '_3p>yOP%EU0r<d^AfyQ>f=lH6)w+IvDdXq(^Re5{v>9aPXPb96-NQ2-G{Z=6j3DbU?a|vuApHaeoxS1=C&plDQB*`NZp3)ta7bYyVHsvXPSb!3PMrkA6l9634TOPnpVnmb_x4!(g+Is53GI);HP2LQ'
    '1Dln+7J9E!-XcG<tve0cEk~WBgXAeA^Zmayv-0v=Dx>FeB4abT&T#T_H?bc8K=7Z3<VqN*MwBE_1B5+vCFq$C${i09#{^B`P`Pk{LF$F@r@jlo8btOxgLPF&eTLN+5f@e+ADR6Mx4{3G^FIe1#o_ve'
    '<{XYPS6XhrB?0nV;hO$ke$8UVT;$Ow-4GmtBCG4)&>VY6FqBTFd)(Y$+{)aDK;J*)7tlG^@C)5T&kMRN)I;4YfhL@^ecEUpZX<1g=cq1ov;4$wat&f4Ey5yB*iy6UlN0;Lx&6xpALI1>7T=y#-)(-p'
    'jj~&3r+(RQn_lFR^as;v3f|9U!aAJWWiK>KxB>|Yv>X}j@jqSG{j_cvI#HqcTh;2TR)VPel^IyOX6<^xi!lOGfk!u_jEGFmEV%ju$R|hZ_&2Nn<hr=1irLU?zjt7rywNBE*X__?=GthB7Q*)pT;M*~'
    '{JtBw{pZ#DE7_@dDWE1gHrKS4D$isQ!0;FpOf)gd3k=rB*1A;(A~}kVzoR_YXN!L4?Bn;?o9ACY+jE+HeP?l>vsP<a%~LmOw^a3?t<1MJ8pYEoZ0ZW{t>^JNI@-uO7t8NC5JVbVdq+$X0?u^wHbioQ'
    'W0_H$rH^~nw$|S7Jh~`YH_V338eVWud5i4@@^z<ZoSwM%T)ytl$i6dkqE!~)%m$1lCmf<vjU~I8Fn+Cl9vno?w<j#8$J1&h($Ag7BLr4r$-C`sfo5Bt-&x7s0Q2bj>34JUb7%5F<{(1I_VeZrvKSqu'
    'T1Z}Fq|q02c9`wg7w+Aw_z{ITaHU*7<Z^Y?s_afmUY9TTs;N0*IJI2d@~+#mSXE3~n=YE#+Yk+uSzBlJ8&CmML=kG<{{9bM&1(JR7`90-T%a}%l<z%6AVl$a0`q?EneF85ySX#jp5fZrEvGdSj{JnR'
    ')|_tqaq4^hMG-sEX<(vM7(`l%?T+`^p^!|y6J!A81R+|#$xe0p1a0O8)$cz`1MPo?@4))`6xtaG{K?5e6$lJDj?2|~ug9mJvtw2}Z;|TM8Q}sDgp80RsrUPH=e~W_Tm4z~?scjw)MLS*W`92c=ugml'
    '!L8*()2$du;p-mJ3-lEXPO(R{L)LLZgrsEbx@Eu9A4d(|g>K1joS`<UYXAo|!Cy8V4l{Hb+TwO$i|ArkYgny}ZNvT7P!OO`He=oTd|ma8Rf;{uUf%pIV6!e;xK*+?Fcg81SwumK?Xme|J>i2NQ|Fx^'
    '^xo9T`<2ewk+wX-Ow6wu`(2CF|2gh|7d|ZhOVius?jO5{W#-2UqC5~Xv|YR84_YwWAS+-3j1L*j18@NN@bR99XR(t-D0d(2Ke*!7iZ&>DD1?+l7cwP^{L=_V;`rK?RbjE7_(7dGZ*&{f?E7C;^*^(A'
    'htQ)F)i=i@>(3GN`E&XHUjY1GPOly;@(dW)kEt_ah*RUK>VHIkr|Ay5d4cil^qfPlK!4V$MZl0ruXO$Aym<wXP~YcM$TuCr0#nfJgoiLJdLVuNTcLZTa{_wB)7G*EeBG{}GzjZWf+8CtFhi!DJXvPY'
    '=AU3I&2=q~2R-f6)qDtQyJQxDEvd=JB#Y!js7kh1#p0fz(%jtUWzU8T5!r2BN=Xu=np<&0(^&ecSJ0*dsBo$xOQI{S$nTx^&wpPYdhdE+o-FmACyTOyo0;sVa%+5MB38Rdc0tnWBU0aGS3a-F<E-Rj'
    '?1@Pg+9jxUlRYhOM=f@ir4n(i8;OH}>^X&!1EY+D&ADB4-L2D~J~{3lrLDK%gbAkl?blacU17I3kC+P)L6SjD05urwkiehtuYvy=K=|HIBnr=v2ctneL(w932zw4HqV`GUPZGQ-b(y>wh+2IQpVITv'
    'c98-%qMDY7L_Xpm+;26YoyZshR0xh9aS!!4v#%zR(;b|A`b`s4M_n>n8bl*Hr&x)i`L%{y4nYlCQ#Ia@j1De2`hIzb?wE(n9(8OiLPRLUC=m%jAIxH58=?Ai50m@E-+A|+qx4~XpuZ2ZYi^?FT;sd>'
    'NX?@kMJ10=X#3x1#Y`H>qfUJ}!|EFZIUVQW`PY>2XjenU#&_of^$DDRU<(CEDkKo)4fy}HQ3$iz7X!2Ib*G!rm=33RnNb70fI$#~K>~3ao@6^Wknj%yUQrnMVc+1RM~)BGmuSl9$on5tmM{kKIFRgK'
    '6%e!GqL->Zz2X?!#w5g}3KPI$Lj-Qv?(59Pc%FO6G33_>eN15hiW}nN`BPi%X$YYbme}U0p8}G;H;Y#yxpdodl&r}rFv2j!g3=7!GAS<o-QIjpg46pu9UAL7)Q1HrwkV)M_`o>%2H1Jp#G^pJ0nn81'
    '-}d>do)A)horM(<KBx4fYO{483ib|I^vyfa+-^uPF0c`(EY<Gh@?)ToJ)+gA1Z@R34%3#p=QG4kE4@;nO&xl60WDIe+=S3Y6>y`5D^d`yAEi!y0`V_7rs=Kl&mMQ`52=b+j?ci5C{XL|Du|@2M5-AX'
    'f@m_1fPDu<IrZFnM5AJzqj(@xJsm~(7G`V`zzo`|d<5Bf0b^Xzq##G#tNXhl(;+jWkClTT53S}yo6dQHt=T*x$Gtp*Tj4Vymon{65PmB=Gdp;M85G41P-3qhR(nUHhH{?C1hVc0+;4}An#8F;MUHRN'
    's|#oPeyuPZyMv(+2t_UdeSl;EKZ{S%%X|Kgm2`;-BVWOG{ajf-?6_cGzW>3U9hk8mpFBHY_DivqwbNw6MedY#K<4kH;)mw<&O?XUyjASFlC|Q(W`;ABw)28zp=Hi349&w+)~ffs3t2fyiy?7@;q2DB'
    'j$?#EsPQ<mW|K(m&SE9Pn4QU%%XGRvRnvzLS~hqVhS)5<n_tye(-5s+7Ra*WR;^x+vX40Aa3-s8S1#V~j+P%60C*?ByVpsRi&=47QWWM*LIoQOQ&W@K>?oo3FOY(n>!)#puB*J%H|D=n7!fBxF<~)8'
    '631!mVzs7d6x~n&SGR6Ep$EKsmKtjd+Edw*&vWda6KC5c*=1(R+B;3t32H0pt|rp1O$e5I-9geU{S)YqnM=~%&N*dV*!ta&i6C8`KO;sb(DjY$%nbTp+#i?UT^?NoQ__z$v@LC5K#-7<B=pl?#e@Jv'
    '2Ouh`5rBY+UUmys+xJGrd$#W1qo1Qkp)2F34THqMNS`%QM0$4{4BsQ#Zc>Su4nQV?dVoJ->xL?Nh6<aarL+X&*mCw{6oe1lex$?D2gwl^qa~Q8Ns_`bU_o9$UMbrXY#>mBL;{2uX_k#`uHp=e0ca7I'
    'i-p4_ZIE*xd1odQa5I0m<0@Uff<l}e@Q&br2$chDr&C$x@I$YMgLwCL;bm2ePQtKkP!$|_R=t%<o)~D5ezZ_4S$|T$Teb1Ce2IwlmDU1ZQxht9vaCI!RNF6@lTfNroNmlvDuxRiXwyj5Vw|M0YY?q('
    'KU2a+r3Ls+8S8>1@w&dPw@h)3GR&%znkqIdwB_NfhYT9B^#u=AeI}EaI;WRh^7y!GYBQpJXtR07cSyNhUnjM7;#o6$TUG}*R6SXtTZ}3>H$@~>iR(CMaI{wBDP-{5k&`+VHwNiv(@XxhQ>Ml@-~>5;'
    'eJ=U}-SXy(%iMVQ*Ho`LxYKrU63vZWc2<dvIfmYD-_)lxO{4Lkcqf>sfcg_=1(J#l&DF6}j`w**9O3=X^gN`BlyD+Y^mXyWj}CpkT{+3!aC7?MNY@^|(#tyqjf)ks52`hG*!x{M($~>0G5FGg@`Et^'
    'gZ&^EUN+6sl`DO7Wdm;<R%cdNIC0b(D<TRgf?hKK30-rpg_pJE)5ZBa2gM+{*>7$bpy3>&GOGe2sKNnNQJ!{PN)LPQ(yv>)U+i4CyO1Bk;1}fqOa$l$kB5hCw`}CcURkScr1jl%pjSujN{$UtDSOmZ'
    '5LH0P*L^_)tFX9N(i-X~pQSScU_H=%rt3pgkF631CqU3SxrD2f5D<fjef`e&@d^I}js5@b)BQWZ+`~DUnq#l}%rQU1na<a2)V2ph7+2+B(WoNXi6JbBLQ$zGQ-zeH8EPgY2)eqWB|u!FY)DGUpvEyp'
    '6x<_9?$E?CZk*9j6;mh;RF|ef-f?_XSVSHOx1a<3SpFb+TpR>15V?&Hty)&SDwy@~djb&VX2Jf>o|FRypJ~%#Gr<uR?}irCVTMtRW0rxY8;lVZRWX@+`kpBLY804<`Z#z#>&JBMqvkGa0X5grF?rC='
    '7B&#xEdpH^T?Ggjq9(&W1h)Pf_*p|RJ;D$2z?2T%mf^ua#5g&_0R*suKo|oAf<Q<K&>l`!jb1l|6BJejV^t)gxxXbQR;+`?)n5QuG(=Z|V97z;D_PxJ@OgY>2ti0Q&!Mje${i5co}l@Nh#HJfXpmSp'
    'Gbbbrv47m)I6RaJB0-FBB?tf@gGMN57P3MR!U+O}WFr-5LdvKSSy^H9FJdB`mgetX_n58dsf2dW2@Q$4Z<2s>Wzz<XHvZZKJJ&tAyPLt6IdhmC+l7|*Lk1vNf`ql|fGVp03Gep$XSYYYESiZ(hLn(W'
    'Xc+a4k6vIV7xumL^`%lrWW*-|9gHBNilbbxI~W_-IAs|)L<FwUB~wxqB0!*t2muu;&0TM=v&ZoG{Qh2i&(G=AZaG0eXw9B+`OLpP@s;$?s<Tet2sX@T8bi*XL7rSj@r+B~HnXA;6-{DE4R*8=Z1Xxy'
    '*&?AKKBbtwu2v={wf4nUtsvPjaT7<1Eb6Av;+K{{aFlnBIBsqRHIs`jChUC9nDcHV6<d%EEe{1kt)|tDbao_@{kdCUr0@|9u(-vqm+tO$Z8)-tLThi6tKBV}uLnW$_)MDbjCY`^tnYyAJ^&&eeZi2C'
    '0y&4`KFEO)#Ut%M+Woav!-7E+5ft2nekX9p>@JAmz#!*G<KxjlKa3W$z8bLwMG#R{Q3JK62IRyzV_*gb7<LC$I~Nv_q^|Ir2W`ilp2%#6P-bQL<FgIbtG3DOCUo^x*<>$EdVwX+6aajeR33-};{dpw'
    'wh+TL34q!zU6h#@oxzRan#j{&2?CMrvKgr>=uynTkwuD@1W8tHZkXenw@MqPKV7j%C;)AzN=>2FZ)4pZPBEh5pLw<+AnIpW&g^qp&UW{l5J`<%*uuiLV8=kwn#5C7JOBnE^^pST4MW(hSi9@T>GUzR'
    '-Le-7Qn>cH<Y$(qf+(ib;q8AuEx{20>BZYOXUBJJeA@}ZzkPIh0Vv#~LB5B-Icb=@#zrKVCvg4l{sHvk5Rfbf=m>JCf~u;iN~)@=8mNM(DwSUU5C|>IyLgZ}{A2*d#5^UuAOJ<8h7(fMDmDTsH~?+~'
    '2DS`r`HgIBJ-p7f_E;M;1_B+LQG$EARA6f=cce7@)s%pUmRxsl@Ey~qXD+Vp`O~pluKR4ZmOW<Z#xtzyebtg^dYyc5i2CVp2TdS}gx}-7@_3{H@09|<+0r@ruuP)d=n|~q+d>eg5iO!*!`TMYO2EK?'
    '^oc#Mj)2q-S%?qvs;tC}3|vDnaY`4CUsUfUa}4GMAVvaO9g6J+L}HReh&Fs0l-eQs(jbVUN@^t_7($t3AOs{NCH)$M5YKyS@LRhF#b?{8)Ii>cmGUr>3gWqpwVrF{UCcz#5a<#GIXX*AK)Q|i(5Eta'
    '&I6}h)S)+&JpSwpn$1r6ArSKWkSFE-1VykN{mop*htOyh6Y(;Jp4p!o3x;<LWsh7ChwP+&m|`5_t&7`RsQyl~L_|O;UHXoL5v%c_B-EUa(gwVA;nF*}>~+<Ks5&>Q2~cZYp3lgqn><8$%DP@kE2R7O'
    't5*etFXvg-<2>d0I`dwH9VE$Rd3xmh@Sd6yp?_ul*~cyV8cl}EED)Ept3<tR!?c|1vD!Wx-@bP_o%`_6(jdKx!NMXUAdX^Z&A|FVdI#6%Iy;0(--H=xQ8Lw7X|r<ITcl3$3a}Vpz2(5<YSfbwfUu60'
    'wjwp!_H@+ijuB{((q!omR#xG%a696DF5PM0(>dS9X;c%YSr~jWi$ll&aZX6kheulb{imMP$b<coJ;#-RGAqAqjO=H(zWLr`<BkEir&IxP<`hShhtzO72mt7)>_LL8rc0TGnILhwasq@HFd@aSrW36M'
    '>Hy!r=sqyo@0HNwDSbaSbf)foD|gd4+eq0<YC51T)yeg#8Y_{U93Er#_QFc9lGShX8geS9IJ^|-PL}-J8<}r4^#ZWQ?^oY@B$`mljMehn<*|P4k~?zN?K{<-_d0zJ9!oKs-E-vK;rNYPE)~-ITSK<5'
    'nAOV`N{r(cYfd6mYdMumX!o6OkV?-Ws}-aT31!{&rcP-ECRa<yY27+$@D3rZXXvz>>jPzI&e}RIhu01};i_^sZBXL*C?Mbj0e7hxv^WeDxuP8i1yI)%!6d?%1#R5i${D8~KYUC&17b47uNqyCC%Nhx'
    'qCb3j3_~a>W-ZEN4<g0kCCilF@?5YYaQ99z<|xiDMUW8*K|uA;Y5VpI5af{#VkS{k$$+>890sFgwFG4)5K!9#lbTWTPzKg6q)w*rgkY9gklBM_gkYu$bZc_&;5G!haz^5k?W1yu6z=t0l`dtCx?9>e'
    'Gni253lX5kW-)tO)JbMT%v`0DV&sP^r5qwiKqGMAI7(Mm(R$TBS)44_it%>`a&>)G_tEduk7qtLbp2<nM1_s44QZ`7??AwXj?JHak{s$xP~3fiL?!@-!TAJ%{KJcXkNki(@1D4C-t6ky^L*&~t3u@P'
    '*m1z>TOj~SwA@!_7ga1C&1A6Ioy0aeI-+0{s}ZWTIQTj?ZNv8k0S-WZi$P%x;Y0GDwm?nP(0HQQY`~T}7U1_z<t!3ZNYV*HM2Hf>2%tVMyGB!}bNMBQzG81&o@yU18!b~rE?Bz+5M!cf8BjtgGI{~X'
    'hbS?-h=?L2Q2;unE_=A^)2B!B=%kme&aA%%#cmK~1ll0k?t4rC_gzy!BM6#DPzq@=BP3NlbAz6%)L^xz1WPm^hybmP`x$GB8AbBP4Qy5Gs$!SWb*H7`^!mL6YlS49zn|ap2gJCB1~OGtnAJ;QWd)<>'
    '@l*&nN+!dv*zFDl0eZM+4eRUY=h3<QD)Fb6h|U@hmzCq}olYp~7I!7U0f0Vpv;Z%++=6PJCLR_N#};mR4zlc@8BqjC<rxY6Kvq{T>fh-3O&W1mK?H(=Yj5uJ1wL>tr<k+PbVRlST6HVRK*S6Q5_V(p'
    ';Tk=^mW1CuvEvIH-m)ATnn=Rb2VGEJ8sgyw^dn~GbFB^p=x!U<b%D^N9F8i&ULX??HG8hVj0gBT4=7_tmok1uxXv}z?Dym^YALx~!nEPn$I@zP+>|G|JrH~GBBve#>XJI*0o&c<sSh#mUgvzQ3@t%H'
    'g>4VN${yfgL4gJY2mz$$WASry6PdN#2?l%Wh4sGi&&7)(fgp#IPmpEPTp|TnXR#0*4<i19?yYnga8rkGiPXuRDx*e;3$gT%CI?DnrpahWf`;Spp@7d;L-S0qld1r2NOlI5^$4kP*1|BOj0RMq;-Y4D'
    '81!?wj;4hr*+lICwMYa`9>IQ)2!m$f9|;DDf^mc)`hC*g+tPe^1!>x{XDG5_N9700a!{&I#|K-1If;lEK?~7DA_3;POklS{nh0EBN|IE2*g%E^A27p>p@8G}?;unpe;ZtMLNGLt?1Re*Hi0XItsoR3'
    'wd?4;gnwQ|LH&*SfOP0;FhDQUWAopQ-4^a>AaRgc0a#jm2K!#<A(RJ)vp=fT3wV0@QdlCe3`Uns(K*|isGwY7uu%xuYEPR(fXg0R76moMI+jNSNL`^&ix&i=PF2YT2x62f(rJA3#Ral1AfZUqLfP30'
    'Sjl?9oelbJ$T(87z@7H=ZwE2JiLgvNU?GKDiTWVI+tULvxTlpk>=mt&TtvMx5;77JAO&;==k&-%W09$nw7ENC@B^0;ODhRss!lyAUa8b{pD$9T-)5dsK_Xb72e{aIoz^>UWJ56w-kR(!UF&j!#Shga'
    '2|ocq+9;#qdIaCq?b4xsb=MZLQfTP+;fEp#*^#>U+-%afHsU%-Qiy4xA|gWwL(m}TIrf-ipt24bjh0!40YhO#fj|&jD!7JklC7pihD!wn>(iWji|CmgOi$l54~>!BBP0(+?YdGq?a7ck#)_ChO^##l'
    'OD>&d5{!U>7S@(#FKUv&+iwx%2RvkoNWdYXhyjvf5+Dgk5D8KQP=pd7uYhjzrG3F<0M))4OjO>8Bga0#`T+h=;g~?Flt6y@g4qEA76+r(xZHsa(B;AluUHenchit4U(B*y01T#!91%4X^dtQkK|4R|'
    'f_J>t*#zf;vMRzNkQo9-3J@T@LW*7`4syD|1_sLrv4Sv0en754-g$i<9iO_B3*frQNI-%|a`urGScwGBhDppE!W^8TxB>|6co0PWf&!=7D83AKIeu-o>iM&w^-02|6fN|}^GEBYEU><snz!iv@6dDm'
    '#c>v-*t<Z{XC7W@3Lv2A*LU9SLwRXKv8DK0bjJ3NF?`ugmDe`eo!1c7cf-bOOJS6IT;j&q-Dd}4W>cdu@3zr?ffr^?_ua4A>RZ0hzy|kTaxk$?mQ-U`xVlMI?E->W8APKa6QOqYjit10+Lr2O?73V#'
    'l;@MH;{*})R*PzP#lcbISH0hHc8q4&fv3#Dg?0nL0T3btJtEzNAb^l*_KeSN+3@(8di(j`Z$AGT^IXX;H%dzQOu~0g?(q|~5U(f2-$z73u@bu{Gdt?c<eog=W^_=ayAO(#E@q1N(a*gy)8~5!N>#lj'
    '#&dEZiubo`R?OR0Od6)j+k9C!@(XKRYZp?TEOT0?(C`~p)9`iYTkHEJ^^U*6Da_w`e}>Nurc=uH+cL{Gd$+DPU_AXIc87O0!NK{@q~}b)+z0^xe-H`Ie!ugQNjv@<>#!e3QUE#v>!$>V@P41D<{V<0'
    'dssN3Ic8-E&=Bw7o>Gq(2k?MlG&Bh3i2;Km5(J9Gu?tm5e?ULv^#jA|pYs2kaw5pcp7EKEd|z*$`M)RFCycLvWmQ#GRF}g8OmDhu4X+M!e2;On0d5ywSZ~SN4n0E0nu@9dT>!_R2y8(ll69riB9{m7'
    'k4{vjKu?8>RYE@z0JKC=3<wa422LdnSyf<2hwF(Em2@R2(1RkNA_yE25$OXqRgp*{5P}4R3<BH~DTD&|5YW~MJYQcA=jt%h4@1|~?yUkCB1>x<ug*cdO$0~{3`Kd~*;~{AUBEc^2&ZAjhCt>)&>^cv'
    'u%}2EAJIVzS`Kd6jzBmrhfFI=c!2~F<O3LzXVz-qkU(7>0!zF{&JpGWK%9jhXhrFpSV)vkJ5XP(9&0*tCqf5ks?unP3@j(KWxsw`Xmh)wS`@YjsZ2SAHpFzWb6gIW&UJ+$5-b@uIhf6;Q<PAFy##?p'
    'l;w$ppA#Ub&=jzWa0`Y51J1BRBhsS4;mO$W0rC*-29M?`N+rO`i>bL_)juECBmzWX4-I*KPBrQs%+TXn6VLGLJMwya1x}(o3AlHdlyi<jLh8ghLE~k4u`1{X>IDY^@0ZJ7f`Y6bUerG9v#G!#t9rx%'
    '>G_MoJ=Z^hr2?4w$1GSmi1(<M#2R#<#8Qt`&M9*ej9attvTaP8Qg-G4X0DdgNIOv@(K|R()XMK6J-IT?DD9&#q=_exo{zmt)g~>^@}63K{ByNPwzS!~D|sfhEgeY*xt10tKTPdtd2Gi3Fg@%bpn^Rk'
    ';A!FPaPkmy+cVzBl5A{-MtR-0sMEeD<ut{nIfg}G_&ku1K^hz&ijGC+4ZInTVE#GymVppWH=!tvSRUPY)U5Dc*TyYakkq^SqBmk04+0=~4zl5y$yjRdvtXBMLZ<czCGMrv4<*qQupT0-xO`^))=WSz'
    '#>pfHQWD+MyarklX$BJg?>m9x^>6F)p05vBs5)`bp1$bFGCEHNC}K_UVmmh11Nveug?<F(A{~ava}iFG-*a+CNI>XWu9oN^N7>N(4ybfK>>siR?DvhtiP}Nvn;?o3D)gK4z8zDn3L#L0b*r2oLIFm*'
    '4M2|*0CWe~6p*>_mTSmn8Nly`<PR8C9UTQ`o{JhGaG!e!MvQ9%3W=K14EV_){E@CR?T^fUA4T&iq5Vobl7U-d6wRT5wnYJ%Xraf2H=f1*BkB($upfKscRR17W<i<p!&B)oC71v);rX420|=>~N7@C!'
    'Hi-y`U<wKp=gj@ZOeHj}4+)@JDPF0F24KIpKd2Ek2Z#y4+74-RXUDoVO&NS}^kXDz;GcPZ1Cc!>s05uE1_pjJb}61lQGZ_ELN*`B?i5osdkB^o$k<4u1+if)qFM`33IZu;C6OQnh0oqX*7WAW$GgDz'
    '>F6^7xSB0<m`6WAcObcvAZd5L^LIP%^_rgakuZ1wn&{E-+VL(Rq8UO(WvD_3Vo8Ki0EGi7!-0^1yMF>AFvg-|2lf^K85@|eK*(AJ1%&#a5Z?FlCb-is{)g$G5&_~0rl&aeC)U7W#Zv|<7@^u}kcvpo'
    't`#@Wo5na4rsAGx9aKA;SOUxvFhW4;P<|XW31{3;E*RFBel{&=go*zjnE8{(<?yQHoXcyp*D;mJNa~@+7Y+p2FIN%gVE{KO79gyONg-$kkgBBt8A3pa0AL^jfgmBQp}A-s@GM+FuFnu4($7ePAXFpU'
    '+7gf3jH|5aq4*63*z327?zQZKu*Hx;5Qsnroyy^TJ9>cS)c$7}!Vx#RbOo@JKW<mJAC^V#Vt*Sw3&;c4wc<F-g%B(yorD!{o7@nrESB`pXM=}+UUA6{0r(sVrsjb9cNEqA)64QZ-c%$H+6Px7l{S1q'
    'L)sjIy|BWp^5%u-2lIFAYIKoiCI@_6f|IuCXk@0L9{(%Psx}=${I;Q(nogIfo$-<3{Sv`)q&{#b!2{O>4_t{ijVU#cm=ozH!7xDM6Ko*CAVEPi4<zsuA8t?_XVLWjNyyP1O;r<;G<u?7he#3w69s@%'
    '@__=2qj%%Yk*P{e`O=eOTHXqGSQef{z;=3%lsLtZqAdgU<_gX~-{A^wz5e76htRinZxlcvKA2ELv|QQ1V4`GD-n2qMqK3Y>5_7s5Bm!z>yhq0)c&Cg)z0hdKmL1{KbAo8*bP2PVaLvp9ILE2wbao+h'
    'AUNT<r#N(m1O}QUW`$C`g`nAPR8!X<v(?UBto)mro$V}HU%w6~-D(GQJ?Gs;<Rjgue7CPT+@W+x{wZ763R9bh@phiIJxre9>>gf(3>Z{IK#~|Iln&x4-Lec8v<VUyKrdPf5OHvzh2)8^a-s#|01)s;'
    '=RFrP4h@90feUSW9$~Opz?+0#BWUH98PqN}$tZcJr?QCyjEz}~c-lr5i4)Tc5LY(~Pa6Ty4IAp3Z8rot#peTBHvc>xq<K9z*|b=MGrAme(vHiUb~6zr3DPBfAQDk{LSl)FoRC=-Np@u&xsDyP4uEs2'
    '48Y<-X2fQ8#NSH}%0V0wlQ;<=G~PEQ6iC2pZtI7C+J8CwN;H1q2sjAyh$!x^<$UD8&kC_gPENZYtUp<@hGR6P49jV8uy<@;=;8qcX*B2yQY|ym*{t9%5IO~AD=u>j;$W3*IWPtdA})~_iIC7%*-Srz'
    '`u=nu9`N)95X4vzSgyy23LmNi=KXqeeNmILtD;N69-%oFleQLgNfPLfvHjq!Q!=UgeEcS%v5xSe_!sBOu5c(Jq4CM`z2>W+ln!cB;7=vwy@qxghyg&fqD%qydwvH4OBHm8Ft-HP^%_OUA_DK5xgDG~'
    'Ub_cI4YOjgB__bE0Ktkxq%a%>QcTPlEEzNiII0Q-u%tsd7+tkloFG;fZgzP3`Uw+MNgZFGJ1P5J&W|IQ2BG8+SDFDN2^w{RsPN;zs&JMzhWbpE_iEBJbbFhFae}`v7remqRbn;nw_N?VD)<eHe@NgW'
    '|2X&Z>D<iqJZfdKZGQ@IgLEm>E0r~w0pE8V0uTs;B$`)Vs8mJIKUiM_wGRL&bvEn_4A5HWOU>iR%T=iMbEs_UCr}gyDhJQPb%s^0&OvONgd)NXCzwAI*YYZNg~9H?F@eY^IULR3z9O^-8T5ShX8k_W'
    'gsF4M1ve$q=H#8^X`xy%hPQN@Ls8HyGIZB4AR;fYUlIpyUv8(9l9V^mC<)+2jk0c^q=1NU9h>{J+cx1f)f1FYdK4yhGIRudpb8$~MFf18SPVYqAkXmm_XY)iu>?Xpnb{H$A_|gti@2CR$dXKaxiFt;'
    '@&UjQ`8ESwuU%pRVj&L01=_v3BY22yK}x0aSg;tS%rJ!19vFu@fW}NQogVze&BsDH27u%ZBZ!n#3twp(fL)MVt*sCeDuhvPONFHr;wgnpm-uTVaHOWhAsmYOOd`KFf_h>lz6d!BcxtYt5TScbFYy5L'
    'p7TKj_dpPM1K2>*ZU6|zAVeTQPETz{?v;RNq3?(MQu&U>P`iK~gQ9(n5cL54-=^16#hGcETX{gd1{&R>ND)(nR75F&6c9k%tNpYOFixg4kVVVUOcabDKbM^gDz)Uu!a{*yd`*4^PPQEzv&(@T4KM(N'
    'a{)0^lpdSbLghq(2+$rp!F$Ed4vL!}5X;Ihc&l&>CMbcR=9}TkS05@akKaRd4p|t$gk#V^uwVlPO{#9o3xUqCgi-Dm!RoT-Fqf)0?ogezq4cPqbmSdl_Zk#~Aew>$%{cB6f_uaz2D~37^qBbAeGj0J'
    'P3q4BOGE{3mIgq8#@jDeU>}W?pi@9}oCZK|2y1}CA{rUvQPVV00OKY16Kr!(A%0AO*ZnF23Ubs!ZOGRDT!aD&UK1>~5(x>UTu#%amJ~;5KQSk@$m|i5vh}!UW18&sJ#B_I>5gVFNwJi|cH)svNku4x'
    'OZ`+qa3a;HlF0{?6k$B!faC+r!OEU6Y}=h&f+Wi<le8HIz&SkYv8l1QBEylTsHF@;vRV|-#*(F>(~=cJXlS=Rr>>0Lu-jmW4n{i%fDl4XZX7e4yGJtx+A?&5Y5ev@Mv;X0jC+=v0TnJsY{`Jm>MGzF'
    'Hs)alQJu0a%*L~H9sohXB@<w9Wr`v(1eP4Y;hhA!5JwLNJGLbe6uFE=F)=JqMPLjg5UmOku?QpxMIoT834)Le2QUKyBG52$6c$p{y2d0Bq{M|GYfvf>Fi?{ZfH-FdASp4x<|cw->wub2O;Co|v_s_F'
    'RML#chjKd|7`j{shXycq12qu9=@H0YPWaKnlFJf`T?P|^;(%yMI_TPkTE+-Nq3shQuD+21hY6@(Syd_$1kc_Wg9s3X8Q-+r7WbSe1_a{cFhHYJ3Qhz*r^%leukn!6K!IJOtKl3{2C5m<wipybNx&f3'
    'kFPY-v{*2p8H7s2unm&Jx5)r&08}8DdhA>}jN@pdj6&j!j3A`llG(d(xo=ALDWS4l%nr`pLS99>Vo0R2U<aV_%NHR}V#vrGYG@z@5TFIj%_Ku5TE+pwUkVf$R%4M=;Sy$0#xD@fN)4UO(8c!GR2U_I'
    ')4X7v34z18pNLC)2_!<sLIL<1V!*0$q@mPYl%t4>QbZ|Osev<m7gCR3K6Gq-fsL|gWDME*1F-Y(zQZ^dU^`Msror73K#*h!2t<bn=!93_FhoUc3{({Fy%rKB+DwTNv1ka8kkSGK76cVysS$|8NQy!O'
    'D?~auDMHWz@@bqRVW4=TL;*;m6%{BPN&swFDY0r(%wSkdDoCi@Dgcr&BcVXZ(w0ey7!)d_NQM9$BoV8}P#+}okV8oY=z^kw<{%5!9M5hbWCsd?u||N2sdiAjHcgY_k3>Cq;cArUOqFs(Vxt6Lju_vh'
    'pg#XaT>SOs3m#eXu%gxN3_uX`)__*ygXKK*G9e2E#l`phh&}6WfqD(?0OBg`4C?Ghlt4Bo2gRFwx{ulnWJv-G2ti~>OhnM5Ya2u?JRrbBGAbY7@QnfX65^a8H^F}q1o=k6hMWb)+y@LKKUYc|D02}`'
    'BckSX{4`*8xzz*C3lvU$%KrWdiBu3%O7xkKx6qiS0PKngx<Z5x>I9cduLkv33a)FrPv~T+e#$gZ&@xjbe-glWH3I@%%w{1Wm7pk(Ig<U5pE4b!im8wyMhPe+Ad4d^8DNNzmMUPFBqXHsiG>}r362Ia'
    'U`(+zuuN1%MMOa%GLnN3K_XNI6bS?a5fF?dAVMTC5J5vE4{XASl?248q8LE^zu?X#FiQkqJRA(uRn5jnrIMBsnj;jdf}&KINAB|AA2hplhyggFDeN1dX^c#&8FfpMQ>Sr?p0*pLPn1y5RZ1e#lT1WJ'
    'GeihgLNbj~ps+<yUg_v4ne^|ffoJluys*{{(RZLEBLi$ofGRj>Lr6dp2=xQ}B5B=RZvpY4^%{#^-Vo(IuHlb1A@3LrAVL8I`h}rrSOg%92nh%P!GIx=lh{QILqP{%7b&qiV22DvQs@wHP$CbEivZG;'
    'V-!x92z0rzG=QF<;zbWk5Ob_W8qr}c<Y|i_BoCMX{T6x6$>PoU5YM~-L}M60NCN@{fWd(hDuhTRQXM*{dOU~>0v~Sxp~X~<AaJWew-}K~3Q%JXwweVV@&~{^ndD=g2M|3Lpsyi*kiGR&zC=*pA8vbQ'
    '+Nz#33`K+n1W-s20TLiWA}k`PL{bq24ZPmUfJpRpczwmKByHpQN|+|F5Iun+Afy2y6Qlc_mciKsgb2Y7heRBkM&+0x4oLWpw1o^HT!={6q@vh+4Xer-vkRK4$DD;)1cHzcljQ6xG32h_LZuy~<e6q;'
    'n;igizet}a!ed{X;%C{00#hhX<^@4BKw9quQac}%3_C>xfFR#OAd}J{+vf{_U;x<>BhVJdQXXA8)j-_?nm*pZ4-!IGL0b3s_VR&@XnmJC4n`vgu)`0!gmv=wdr}io4q#{CC#uI>hlm|C?1|o?=>jUh'
    'X0bS;CfF4b4ioj~z<?Sj2oW%Czr1)MrxD_aA6}sfIQosFXX*|5d7byHQXbofwm)U+K-4k*GdS%!Q33uYTqgQv-gB*KyskkW;-U(``%W`#qfPQR13^fk$I+|oA~3`*uTacwtWMrwJG{ql8=Nzyrj2T_'
    '4B|r-kA2<!AUk}5AxX{cxOSYuNDvr677PIhKwwCSn?)!HqyeG<3-n-AAqP>_`Y&*Y(Pv0m@$%CEv(nD8`1pWZ&_V$Lp%0E5HwaLD0_3M)-_Ta`YXZ^CLFvgb1oq_X`n6m=V(c@@f|z^yxlR8#nG)}1'
    '3}c}?|D80k)2%ic(g;H_JCX23?prKfwrQe7XlMclkN|>&1wsiRpuLV60}VbiWrQZC3%KhDyqv&Be!2}3ex<SfXb}UYfGPunaPV+Hck=vUC-M*GTL0^R%f`D!<08}+KxASco?yf$=N!??`W-n2QZWe%'
    '><=oOg82)PeICt86!29Ku=E?+NW}YDdrQ~@Z_US?D~1q)WbB&Jn4!34NbF)+y5>7TxK2nhn7fAVnS#-Uo0w)~swf(DZK1GZWaKiC6DAOxDmlXicMKOSv-IG(4GF}NLx>*joFQTgI9sxYN|K5Y6lG$D'
    'MO1>62yCI11U-!AisJ~kAjOfWs3{twf2@w0DM&;CP0TrNZb1NPmYo5@1|fzm66KFGm1-y9fMNC+I3N(P9wbtTK>v@?kVt)$6u?Q`JH6Y#_&x#Q4^DIsd)Cd$pvl<$N4^I^5&dw5tb%0Xd#O&C#{)=5'
    '2#(!Hsm|NA-3~;cbH0E!k=W*y7!T<IEFqBq3`r4E;qM6>iWM0fga84F4a+kybrWp*00)tPa4t|n0WT1+Qw9b_E-J^2b011`o?`y54Oi+A{cHP9#K7$i(E{8H#S{*32mzf_I4lof!h$AJb7_N<Bu{p4'
    'c%km_Dh9$3UtZ?8xN9-KBbO38$eL0159nO(-RA>*IzvI&aVf3s40C{_M@DU%$!_12-i%=b<*Fm8KOCM;`3-zA3OSphK#?HfWP!^q86ZR`s!-XUQF(%&G&7F)y+gTzBvZHuAdn!07z_vyAOJYSAEY6$'
    'z??C%u&k=f0dQk84u~BlB+$%grvczO;RO6>ihVvH866bJry{9&0n5Btau8;0peh;dDG=2Q`202GPXCZTQC(fljR`36Cvaqr7aS%iC@?8DA_4jUiiI5rt8r7Xii${65`r`=CQK+s7{Xwc2eIW--WZ0G'
    'WdrsB5~P7IDeU?C1uNuE2rytsyTGb31I!8-ek34CsyipGR<wI*dIZ<h4->3}2!x6xhd<_sv_0Y37K|sr!j7RyOD9-q6gt$U+Ov(r=<%nJmiqV=IW)ZoT<cf3Qa!g6QsMa$MNAgy(P~IVNGK#ANeDoQ'
    '4g?7m1Wbs~(1H+xu_#Ko+K^aWK;bOSk%52^p(sSS2Tdh25krO;MG;LDjV-1whm}PPSfB#MFUxslaRf<uiU1{1VRIuDkbsG6XG{Y~pvp0&fI+27jEe*hW;SMXA6sI0C69Mt8a^FphjJn#n}=QtzK242'
    'b1L<+lhdYWC}_a?9{y`45SH>kQ2^q9co$bt`sA}}6V+ABf<iAdE+j5uHWRH2ylM1kB*PqIx4E8gK{)_=`W;!FPOTZ=fv&xb?akp6`gbe%@elIo%im+@cL*Ty@Mi+u$}+FBU@k`BMw}nX+9f5=n*aqT'
    'PM}y&wgZ)cP_xprMluD&pvBI^dzw~&xU|WqE8ri$A=gRzmna&*2_XQAiQEt!m`MmagdOlAnPO|dL0_Q#XK~k<@pt<89}xSXB!c@i?s>4ygDs4(i~!T(0dS)28B1US<N~O<%&H0R><*1R2#5}~#EZ}{'
    'Lj&s|Ogjh2BjiMB+(a~t0uc;g3=V_b7vM)mfQLHAC5s?I;K76mfU*iv3Q#Nv#6z>QM`KJmY^EJ=)KpH(sBT2kA=$5ct}q6P*e41o9dIZwLBRu(I+-fOdTh{Gj5=l=1U5NB`byZ!43X8;O#(Dz17Z+{'
    'qUiwry^Z1Lwo`;=d^1i(dFO81&xshAVuLa0Aa)EGL@TQ$aK#%>vh5L%-w2JDM7mZ+tD4|Os*VzXZoS4t7>%=;qBqHwJK)SDs8p*VN8AodG=k(X0nASf@yRx3Br4(dSUj*3kJ}J8fa9Tc*;fq>!y_63'
    '2lK#eG%n4$=u9GMhGYs*gpi1aNGrlNs8lE<WCwgM7OE&yL8L?C4?v8Apz-Fu%ho}q5ukD)KpHYGO9?)wB~8QP3xd3XhY|Y?PK^4DNXSX3OBQDmu0<7`S9nKD6V{+D!oWb%6ndvL4eiK*S>w#Sz2w~#'
    '>a`hxk4T1NG>M_2hF~VoyOb(HP%LE2>_2!J?!*0abefulP^}4+j-dg$p-f1C`JPzAM19C`(@RGg(R_#Vj+mNu+7)!ygJ_a1;1Y~~40+c!4ei@UIT+^Vc8o#X=9xsLQ_RiWUwz>G%@3$x^p4M&>`4C7'
    '-TOPYhmlk!ykxW_gov9a3BJH!F9YH_gI7Hn5?}KPp~Ra8Uq3IA^RPz5ssm%pK2ZN5YyhbRiui6FBhJXe3?M{cP(nn6LEL*0`~f7yff59fHwXcr03ZWNID=#dY<8DVp*cl-?0ATa2!JvQiA)AT<abgl'
    'aDb0CZc+YQ1hVe{r=$o4Wwm^WK|IE|?CyJk)62lE(r)fpA$(EHzakyc=>T;Ju*VcoG`0<;e~`ckCC2rnB!r3(K^Bq*05;a8p!JbzC|)%MWCOm!32eb?1z9K+%t@><CY!o#(h)qNI`*WYu1Jx!5juLI'
    'gPc&qR?<L_;-+tp7^S!mcH2is)22~i(=HlBMMPJp`#sO3btlv2e@_g|L{Sf*yPE9iphB!!>35Yhs-SQY4th!mu{nWsJ4dv5;`nGPV1_>^LZilol-PFlk05VoGjsFp)UE`~zP5`RyLBPRF9-&Bgn$Gr'
    '7SdpengN(lF|3wW0}iZ+P2SJ28&5##GJXbzI7x;P7zQE9&iWp|$%oCkG7P!PkDl;#B#B%P?C$#*c|=PMkr1NwIrPs27>ZYtOcJ2d83IOH@OeYb`PLfHgOxR>C~+&m+5%-AGJw%4Lk3S6`DY&^w#e){'
    'dqfhGwgq#SxDaaIVe0|!3`v>M*g2Ol_Brf5!WJPTzBm%FK*2&3L_<VD=s^&of+Zvg1`r(;)G-pXgB5~fY<9VZ9A}>oSHO2WU6VsK46iN*9+U4Fmd)_zYOCXos;13Tv~XA6A<_ZRElAo~N!U0JxIC;d'
    'GDyr`-Q9hw;hpax7@|^$P+L;!8<;_Xgy?IbWI(<UGC)MIunI&7fY72}iwk{BB9_a}uWp%5Mm8iT1+LlTL<nIqW?zSuz#NSY>ohrd%-iZD4#$bW4;#APUxA^#3(mehmVH=Zor)^vXKWcBG)vsf(A_BH'
    '!ReErI^-4Ds^)Nw%@4F90*FL+J6w|rA?feAj-8XJ1FSJzay>=bWGNa@tJT2C9-$0(<$ke;f4As9UG;fxSW0xZ@b>fE%`~{AO=?6GGucHDtZPNP<};ee!;%_yyqVmt9zV$^kl7zhPi!z}u7V*tvKk<g'
    'AjC)_SWA&ipe8_|LV`oFuo@)elTJ1pFcO%VE+}Cm6S6o&NwFkIDItV0ilLcARa8YpL{$*Uh^k2GNui1xr*-+~F?rVQk%pPJb}msY6o5)ec=fgx$_XD!(A3Z>H1Z(`gjgf%?VwXY9+=40l^z%z`IIXn'
    'y`|~0>+!p>jK>k$xDDtj%;<^`9I2Y7@`S#sxr&f%`2w(g!3p<=*I;QyN8C|FfkrR^MnIDj4no9{3N!#?A(0UoAaDt!dc?_eB>+(kC5PEyApyFUAt|^Sn+FYUMK*$ml?)R;)6xg2)ara-E;LU;%%0`r'
    'TcP9^P%I!}OT@7b;Y^-DV%AR3>LrZq>L_X#Hp!_vuca;!(SW@pnA9D%R;r4DaUc>Sl7^cYYFUO5K$!uBuwgF18kbR=!j&+fED$Mx!cf>1y{JNzs8d3MP~BS)i2_oURFo9TX-Y*RG%74ABqAVCps*0V'
    'wcu1Lp$$wDwef62xdKK(?GHZz#B3qrd3kc_Gjjsjs-jIA;Z$&&RseliOcZ^eJgme){P18~^=clzL^r;{T5G3?iy|JEJs@S@S4y>{3M+y@Wso;S8-{BF0jVYeNNQ|YAQ^|j1aQz?N{VOHd7;SdH^tAf'
    '1s-)3n*+Ht0vk!svw>_JRr)H!xG3}Dj@}0p;7g){hZ=+tH3F!?yi`z%{D8m%e&_=wz8%DONALo``Q;=>a@g`RBaN<J5*>a%UawSj0^E@g+JcYMfjfhuFqpQ0Zi;sowDzIAgaPTL;usWyg+%~?^ypn4'
    '(rSF9SKo3S`DKdcN8sZz=y!cjy!uR@%Bx!1wMp*8>>#>>$;aXCic9KWI;tv9%XcZB*ElGcpuD2h)QYZ0MZ?Zec6Ls<eP8wW=Z1LHYyCUD$@ODcO-F-ZR=+n2uk;}^P|;KXe;S9@0WcHjA3{?g0SoGa'
    'UyTAbIKt1pDlsGmBwy+S>Hee7fx|z_389QlxVfpZi>ZJSlrZHNlvoQ82#Taq%nld?h*Amxq(O7J>T`TDGuRnDC!}@(e;<eM^#kTX^s*L+fWj6V$B(4nlDbifOj?L!z(n8xoTKZD{2ZU@3C%RyMf`&~'
    'h;*GhKhu%zKqLH!1J*on4{p8Sfe4^T3Iv?$8sp%IXCUVofTg2BVO0>&K+$wrYzyoUN$}MM;&sRzJjNLkwbN@$QDP_GB<aC80StSBq9}EBbUsN4fm%%SSRC54iMx~HS6CWUIRz#Lru-(k)rfgda6YlO'
    'n|`MWAW9EhY+4k1VQCnWe1dQ2`OJAk(s=W`z{N`meZ7nVJTjpI5mSc$xXK*#l(0d}s+fU-5Fou~V3T=KI8aTnAT4m!0hm*pxew$)Sb&j$EZqfBn{put5;90k6g5DfAFsil_%#)N_2_HAk1&n7SYzKz'
    'i3^y$w92AbVD0&LL%v0cU>-=-5Mv;r9Wh|o4!)(55g4J1lkODch{j6joph?O@DU7*jp6sfy9_{<rjW9-KqAH|%-r$1C>|svArMt71_1HFJgvi#B7p^^J%a~AU3BQ6xSkZ?bcNS&p>y}#HEILI9pRyh'
    'Z^8B-tL=}q{nm<do2#2&a#Jc?tTAAe6l0@?SmqQqMs1#t_{+=aK!qkk87({HOz(g^0|`AolH@LUuumtH&bkHALPARsO*I92(~b}098J?Df)fwof<P0GrjOK+3>s8Q0fZe7PK~j(s{n)ZirY&cXj$vi'
    'WAbbvn1SesiJ=5vs#Fc35#Sp-QRxWYz`F&uZUcz}PpYyEELj5y`%lOCYTsf-Adz4MBElc<6P1P@hRN_Z9NR!3^GOmE5NL2qj6*RoL=ve;l0?K{K_EyA<bm5*_DeP!hjDuyy>&JWSqfhoIOajHCIbZQ'
    'r~4YK5J840b*Wku$bqOdHn~V*(dZ^AoFYCYp+2%AZGtrmCF<m`-ilcRvMQMo8Un%>0|FEPU7!rGStYC?0bbGsArXfG0ORma=DKR3Tdh|wX>E9455`udFzLwv&M5&zF0)^E4ayNMYQjzej*~f}eOsIw'
    '{a6BTd?;QAhBLe))`7#W1#`pT3KP3#0fut^QA|FayX?e^kP-EeER!NsZ49s-2-x_sXX-2h4!}W4iU|<d%3n*2ss*o#1R))uBp!BKnoPlEvdRep=F_+?p}^_&L;?c<AVo+(*Kl26d(4ORJOK@R4$0Hg'
    ')Bh`4*6RS?hA;041b`q&L}I8gAEcoukb(d*e_}pg5RcXtMnygopt+yOq#8(IO7P5>GN3#P1fiJV20(a;KeHW&Mg|~e3Q{2=0wE$MN>Y*0KUjJMN>(&51f`&%7!sf%?ZXM{`xMFbHomu?!jO~|S$kJW'
    'UW97rTbQcW5gtFeN*h2UAON(*6a<VwP%FY7O1NI|1LJ`ppr6-zXR=e6N)-g4`3)C$1QYdz6fjsIa*N0=R)J{ItORxdR>U9xV|SL$!Z8Mo1Z4<lZJboL$w{^_8kQBwF4j%p_mK|hp|doq(zG@}FtD(('
    '<Zf?CNPQX$(i);_B#1~_1H=E3AN&dbg=hX(4p?x(KeuxSI1B>+{9VZu;X**T5mBf'

)

def sha(blob):
    return hashlib.sha256(blob).hexdigest()

def detect_root():
    here = Path.cwd().resolve()
    for d in [here, here / 'course', *list(here.parents)[:3]]:
        if (d / 'artisan').is_file() and (d / 'routes/web.php').is_file() and (d / 'resources/js').is_dir():
            return d
    return None

print('=== COURSE LMS WORKFLOW FIX — OFFLINE ===')
print('MODE: source only; no migrate, DB writes, .env edits, restart')
root = detect_root()
if root is None:
    print('[STOP] Jalankan pada folder Laravel yang memiliki file artisan.')
else:
    print('PROJECT:', root)
    try:
        entries = json.loads(bz2.decompress(base64.b85decode(''.join(PAYLOAD).encode())).decode())
        pending, conflicts, already = [], [], []
        for rel, old_hash, new_hash, change in entries:
            path = root / rel
            if not path.resolve().is_relative_to(root):
                raise ValueError('Path traversal rejected: '+rel)
            original = path.read_bytes() if path.is_file() else None
            cur = sha(original) if original is not None else ''
            if cur == new_hash:
                already.append(rel)
                continue
            if cur != old_hash:
                conflicts.append(rel)
                continue
            if not old_hash:
                newdata = change.encode('utf-8')
            else:
                lines = original.decode('utf-8').splitlines(keepends=True)
                output, pos = [], 0
                for start, end, insert in change:
                    if not (pos <= start <= end <= len(lines)):
                        raise ValueError('Patch span invalid: '+rel)
                    output.extend(lines[pos:start])
                    output.append(insert)
                    pos = end
                output.extend(lines[pos:])
                newdata = ''.join(output).encode('utf-8')
            if sha(newdata) != new_hash:
                raise ValueError('Checksum patch mismatch: '+rel)
            pending.append((rel, path, original, newdata))
        print('Diperiksa:', len(entries), '| Perlu update:', len(pending), '| Sudah benar:', len(already), '| Konflik:', len(conflicts))
        if conflicts:
            print('[STOP] Versi file berbeda dengan project yang diaudit. Tidak ada yang diubah:')
            for rel in conflicts:
                print(' -', rel)
        elif not pending:
            print('[OK] Seluruh perbaikan sudah ada, tidak perlu mengubah file.')
        else:
            backup = root / '.lms-workflow-backups' / datetime.now().strftime('%Y%m%d_%H%M%S_%f')
            backup.mkdir(parents=True, exist_ok=False)
            report = []
            for rel, path, old, new in pending:
                if old is not None:
                    save = backup / rel
                    save.parent.mkdir(parents=True, exist_ok=True)
                    save.write_bytes(old)
                report.append({'path':rel, 'old_sha256':sha(old) if old is not None else None, 'new_sha256':sha(new)})
            (backup / 'manifest.json').write_text(json.dumps(report, indent=2), encoding='utf-8')
            done = []
            try:
                for rel, path, old, new in pending:
                    path.parent.mkdir(parents=True, exist_ok=True)
                    tmp = path.with_name(path.name + '.workflow-tmp')
                    tmp.write_bytes(new)
                    tmp.replace(path)
                    done.append((path, old))
                    print('[FIXED]', rel)
            except Exception:
                print('[ROLLBACK] Mengembalikan file yang sudah berubah...')
                for path, old in reversed(done):
                    if old is None:
                        path.unlink(missing_ok=True)
                    else:
                        path.write_bytes(old)
                raise
            print('[OK]', len(done), 'file berhasil diperbaiki.')
            print('BACKUP:', backup)
            print('Selanjutnya: validasi route, build frontend, dan tes di staging.')
    except Exception as error:
        print('[ERROR]', type(error).__name__, str(error))
