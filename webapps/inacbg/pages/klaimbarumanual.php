<?php
    if(strpos($_SERVER['REQUEST_URI'],"pages")){
        exit(header("Location:../index.php"));
    }
?>
<div id="post">
    <div class="entry">   
	<form name="frm_aturadmin" onsubmit="return validasiIsi();" method="post" action="" enctype=multipart/form-data>
        <?php
                echo "";
                $tahunawal      = validTeks(isset($_GET['tahunawal'])?$_GET['tahunawal']:NULL);
                $bulanawal      = validTeks(isset($_GET['bulanawal'])?$_GET['bulanawal']:NULL);
                $tanggalawal    = validTeks(isset($_GET['tanggalawal'])?$_GET['tanggalawal']:NULL);
                $tahunakhir     = validTeks(isset($_GET['tahunakhir'])?$_GET['tahunakhir']:NULL);
                $bulanakhir     = validTeks(isset($_GET['bulanakhir'])?$_GET['bulanakhir']:NULL);
                $tanggalakhir   = validTeks(isset($_GET['tanggalakhir'])?$_GET['tanggalakhir']:NULL);  
                $action         = validTeks(isset($_GET['action'])?$_GET['action']:NULL);
                $no_sep         = validTeks(isset($_GET['no_sep'])?$_GET['no_sep']:NULL);
                $norawat        = validTeks(isset($_GET['norawat'])?$_GET['norawat']:NULL);
                $codernik       = validTeks(isset($_GET['codernik'])?$_GET['codernik']:NULL);
                $keyword        = validTeks(isset($_GET['keyword'])?$_GET['keyword']:NULL);
                echo "<input type=hidden name=codernik  value=$codernik><input type=hidden name=keyword value=$keyword>";
        ?>
        <div style="width: 100%; height: 90%; overflow: auto;">
        <?php
            $BtnCari  = isset($_POST['BtnCari'])?$_POST['BtnCari']:NULL;
            $keyword  = isset($_POST['keyword'])?trim($_POST['keyword']):NULL;
            $keyword  = validTeks($keyword);
            if (isset($BtnCari)) {      
                    $tahunawal      = validTeks(trim($_POST['tahunawal']));
                    $bulanawal      = validTeks(trim($_POST['bulanawal']));
                    $tanggalawal    = validTeks(trim($_POST['tanggalawal']));
                    $tahunakhir     = validTeks(trim($_POST['tahunakhir']));
                    $bulanakhir     = validTeks(trim($_POST['bulanakhir']));
                    $tanggalakhir   = validTeks(trim($_POST['tanggalakhir']));
                    $codernik       = validTeks(trim($_POST['codernik']));                
            }
            if(empty($tahunawal)){
                $tahunawal=date('Y');
            }
            if(empty($bulanawal)){
                $bulanawal=date('m');
            }
            if(empty($tanggalawal)){
                $tanggalawal=date('d');
            }
            if(empty($tahunakhir)){
                $tahunakhir=date('Y');
            }
            if(empty($bulanakhir)){
                $bulanakhir=date('m');
            }
            if(empty($tanggalakhir)){
                $tanggalakhir=date('d');
            }
            $_sql = "select bridging_sep.no_sep, bridging_sep.no_rawat,bridging_sep.nomr,bridging_sep.nama_pasien,
                    bridging_sep.tglsep,bridging_sep.tglrujukan,bridging_sep.no_rujukan,bridging_sep.kdppkrujukan,
                    bridging_sep.nmppkrujukan,bridging_sep.kdppkpelayanan,bridging_sep.nmppkpelayanan,reg_periksa.kd_dokter,dokter.nm_dokter,
                    if(bridging_sep.jnspelayanan='1','1. Rawat Inap','2. Rawat Jalan') as jenispelayanan,bridging_sep.catatan,bridging_sep.diagawal,
                    bridging_sep.nmdiagnosaawal,bridging_sep.kdpolitujuan,bridging_sep.nmpolitujuan,
                    if(bridging_sep.klsrawat='1','1. Kelas 1',if(bridging_sep.klsrawat='2','2. Kelas 2','3. Kelas 3')) as kelas,
                    if(bridging_sep.lakalantas='0','2. Bukan Kasus Kecelakaan','1. Kasus Kecelakaan') as lakalantas,bridging_sep.user, 
                    bridging_sep.tanggal_lahir,bridging_sep.peserta,bridging_sep.jkel,bridging_sep.no_kartu,bridging_sep.tglpulang from bridging_sep inner join reg_periksa inner join dokter 
                    on reg_periksa.no_rawat=bridging_sep.no_rawat and reg_periksa.kd_dokter=dokter.kd_dokter where 
                    bridging_sep.tglsep between '".$tahunawal."-".$bulanawal."-".$tanggalawal." 00:00:00' and '".$tahunakhir."-".$bulanakhir."-".$tanggalakhir." 23:59:59' and bridging_sep.no_sep like '%".$keyword."%' or
                    bridging_sep.tglsep between '".$tahunawal."-".$bulanawal."-".$tanggalawal." 00:00:00' and '".$tahunakhir."-".$bulanakhir."-".$tanggalakhir." 23:59:59' and bridging_sep.nomr like '%".$keyword."%' or
                    bridging_sep.tglsep between '".$tahunawal."-".$bulanawal."-".$tanggalawal." 00:00:00' and '".$tahunakhir."-".$bulanakhir."-".$tanggalakhir." 23:59:59' and bridging_sep.nama_pasien like '%".$keyword."%' or
                    bridging_sep.tglsep between '".$tahunawal."-".$bulanawal."-".$tanggalawal." 00:00:00' and '".$tahunakhir."-".$bulanakhir."-".$tanggalakhir." 23:59:59' and bridging_sep.no_rawat like '%".$keyword."%' or
                    bridging_sep.tglsep between '".$tahunawal."-".$bulanawal."-".$tanggalawal." 00:00:00' and '".$tahunakhir."-".$bulanakhir."-".$tanggalakhir." 23:59:59' and bridging_sep.no_kartu like '%".$keyword."%' 
                    order by bridging_sep.tglsep";
            $hasil=bukaquery($_sql);
            $jumlah=mysqli_num_rows($hasil);
            if(mysqli_num_rows($hasil)!=0) {
                echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' class='tbl_form'>
                        <tr class='head2'>
                            <td width='22%'><div align='center'>SEP</div></td>
                            <td width='23%'><div align='center'>Pasien</div></td>
                            <td width='23%'><div align='center'>Dokter</div></td>
                            <td width='22%'><div align='center'>Diagnosa & Prosedur</div></td>
                            <td width='10%'><div align='center'>Status Data</div></td>
                        </tr>";
                        while($baris = mysqli_fetch_array($hasil)) {
                            $status="<a href='?act=KlaimBaruManual&action=Kirim&no_sep=".$baris["no_sep"]."&tahunawal=$tahunawal&bulanawal=$bulanawal&tanggalawal=$tanggalawal&tahunakhir=$tahunakhir&bulanakhir=$bulanakhir&tanggalakhir=$tanggalakhir&codernik=$codernik'>[Kirim]</a>";
                            if(getOne("select count(inacbg_klaim_baru.no_sep) from inacbg_klaim_baru where inacbg_klaim_baru.no_sep='".$baris["no_sep"]."'")>0){
                                $status="<a href='?act=KlaimBaruManual&action=Kirim&no_sep=".$baris["no_sep"]."&tahunawal=$tahunawal&bulanawal=$bulanawal&tanggalawal=$tanggalawal&tahunakhir=$tahunakhir&bulanakhir=$bulanakhir&tanggalakhir=$tanggalakhir&codernik=$codernik'>[Kirim Ulang]</a>";
                            }
                            echo "<tr class='isi' title='".$baris["no_rawat"].", ".$baris["no_sep"].", ".$baris["tglsep"].", ".$baris["no_kartu"].", ".$baris["nomr"].", ".$baris["nama_pasien"]."'>
                                    <td valign='top'>Tgl.SEP : ".$baris["tglsep"]."<br>No.SEP : ".$baris["no_sep"]."<br>No.Kartu : ".$baris["no_kartu"]."</td>
                                    <td valign='top'>No.Rawat : ".$baris["no_rawat"]."<br>No.MR : ".$baris["nomr"]."<br>Nama Pasien : ".$baris["nama_pasien"]."</td>
                                    <td bgcolor='#FFFFFF' valign='top'>".$baris["nm_dokter"]."<br>Status : ".$baris["jenispelayanan"]."<br>Ruang : ".$baris["nmpolitujuan"]."</td>
                                    <td valign='top'>";
                                    $penyakit="";
                                    $a=1;
                                    $hasilpenyakit=bukaquery("select diagnosa_pasien.kd_penyakit from diagnosa_pasien where diagnosa_pasien.no_rawat='".$baris["no_rawat"]."' order by diagnosa_pasien.prioritas asc");
                                    while($barispenyakit = mysqli_fetch_array($hasilpenyakit)) {
                                        if($a==1){
                                            $penyakit=$barispenyakit["kd_penyakit"];
                                        }else{
                                            $penyakit=$penyakit.", ".$barispenyakit["kd_penyakit"];
                                        }                
                                        $a++;
                                    }
                                    echo $penyakit."<br>";

                                    $prosedur="";
                                    $a=1;
                                    $hasilprosedur=bukaquery("select prosedur_pasien.kode from prosedur_pasien where prosedur_pasien.no_rawat='".$baris["no_rawat"]."' order by prosedur_pasien.prioritas asc");
                                    while($barisprosedur = mysqli_fetch_array($hasilprosedur)) {
                                        if($a==1){
                                            $prosedur=$barisprosedur["kode"];
                                        }else{
                                            $prosedur=$prosedur.", ".$barisprosedur["kode"];
                                        }                
                                        $a++;
                                    } 
                                    echo $prosedur;
                             echo  "</td>
                                    <td valign='top' align='center'><a href='?act=KlaimBaruManual&action=InputDiagnosa&norawat=".$baris["no_rawat"]."&tahunawal=$tahunawal&bulanawal=$bulanawal&tanggalawal=$tanggalawal&tahunakhir=$tahunakhir&bulanakhir=$bulanakhir&tanggalakhir=$tanggalakhir&codernik=$codernik&keyword=$keyword'>[Input Diagnosa]</a><br>".$status."</td>
                                 </tr>";
                        }
                echo "</table>";           
            }else{
                echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' class='tbl_form'>
                        <tr class='head2'>
                            <td width='22%'><div align='center'>SEP</div></td>
                            <td width='23%'><div align='center'>Pasien</div></td>
                            <td width='23%'><div align='center'>Dokter</div></td>
                            <td width='22%'><div align='center'>Diagnosa & Prosedur</div></td>
                            <td width='10%'><div align='center'>Status Data</div></td>
                        </tr>
                       </table>";
            }         

            if($action=="Kirim") {
                $_sql   = "select bridging_sep.no_kartu,bridging_sep.no_sep,bridging_sep.nomr,bridging_sep.nama_pasien,bridging_sep.tanggal_lahir,bridging_sep.jkel,bridging_sep.tglsep,if(bridging_sep.tglpulang='0000-00-00 00:00:00',now(),bridging_sep.tglpulang) as tglpulang,bridging_sep.jnspelayanan,bridging_sep.klsrawat,bridging_sep.no_rawat from bridging_sep where bridging_sep.no_sep='".$no_sep."'";
                $hasil  = bukaquery($_sql);
                $baris  = mysqli_fetch_array($hasil);
                $gender = "";
                if($baris["jkel"]=="L"){
                    $gender="1";
                }else{
                    $gender="2";
                }

                $prosedur="";
                $a=1;
                $hasilprosedur=bukaquery("select prosedur_pasien.kode from prosedur_pasien where prosedur_pasien.no_rawat='".$baris["no_rawat"]."' order by prosedur_pasien.prioritas asc");
                while($barisprosedur = mysqli_fetch_array($hasilprosedur)) {
                    if($a==1){
                        $prosedur=$barisprosedur["kode"];
                    }else{
                        $prosedur=$prosedur."#".$barisprosedur["kode"];
                    }                
                    $a++;
                }            

                $penyakit="";
                $a=1;
                $hasilpenyakit=bukaquery("select diagnosa_pasien.kd_penyakit from diagnosa_pasien where diagnosa_pasien.no_rawat='".$baris["no_rawat"]."' order by diagnosa_pasien.prioritas asc");
                while($barispenyakit = mysqli_fetch_array($hasilpenyakit)) {
                    if($a==1){
                        $penyakit=$barispenyakit["kd_penyakit"];
                    }else{
                        $penyakit=$penyakit."#".$barispenyakit["kd_penyakit"];
                    }                
                    $a++;
                } 

                $discharge_status="5";
                if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Sembuh' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="1";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Sehat' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="1";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Rujuk' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="2";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='APS' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="3";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Pulang Paksa' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="3";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Meninggal' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="4";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='+' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="4";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Atas Persetujuan Dokter' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="1";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Atas Permintaan Sendiri' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="3";
                }else if(getOne("select count(kamar_inap.no_rawat) from kamar_inap where kamar_inap.stts_pulang='Lain-lain' and kamar_inap.no_rawat='".$baris["no_rawat"]."'")>0){
                    $discharge_status="5";
                }else{
                    $discharge_status="1";
                }


                $nm_dokter2="";
                $a=1;
                $hasildokter=bukaquery("select dokter.nm_dokter from dpjp_ranap inner join dokter on dpjp_ranap.kd_dokter=dokter.kd_dokter where dpjp_ranap.no_rawat='".$baris["no_rawat"]."'");
                while($barisdokter = mysqli_fetch_array($hasildokter)) {
                    if($a==1){
                        $nm_dokter2=$barisdokter["nm_dokter"];
                    }else{
                        $nm_dokter2=$nm_dokter2."#".$barisdokter["nm_dokter"];
                    }                
                    $a++;
                } 

                $nm_dokter    = "";
                if(!empty($nm_dokter2)){
                    $nm_dokter=$nm_dokter2;
                }else{
                    $nm_dokter    = getOne("select dokter.nm_dokter from reg_periksa inner join dokter on reg_periksa.kd_dokter=dokter.kd_dokter where reg_periksa.no_rawat='".$baris["no_rawat"]."'");
                }

                $naikkelas=getOne("select bridging_sep.klsnaik from bridging_sep where bridging_sep.no_rawat='$norawat'");
                if(empty($naikkelas)){
                    $naikkelas=getOne("select bridging_sep_internal.klsnaik from bridging_sep_internal where bridging_sep_internal.no_rawat='$norawat'");
                }

                $upgrade_class_ind="0";
                if(!empty($naikkelas)){
                    $upgrade_class_ind="1";
                    if($naikkelas=="1"){
                        $naikkelas="Kelas VVIP";
                    }else if($naikkelas=="2"){
                        $naikkelas="Kelas VIP";
                    }else if($naikkelas=="3"){
                        $naikkelas="Kelas 1";
                    }else if($naikkelas=="4"){
                        $naikkelas="Kelas 2";
                    }else{
                        $naikkelas="";
                    }   
                }else{
                    $naikkelas="";
                }

                BuatKlaimBaru($baris["no_kartu"],$baris["no_sep"],$baris["nomr"],$baris["nama_pasien"],$baris["tanggal_lahir"]." 00:00:00", $gender);
                EditUlangKlaim($baris["no_sep"]);
                UpdateDataKlaim($baris["no_sep"],$baris["no_kartu"],$baris["tglsep"],$baris["tglpulang"],$baris["jnspelayanan"],$baris["klsrawat"],"","","","","",$upgrade_class_ind,$naikkelas,"","",getOne("select berat_badan from pasien_bayi where no_rkm_medis='".$baris["nomr"]."'"),$discharge_status,$penyakit,$prosedur, getOne("select biaya_reg from reg_periksa where no_rawat='".$baris["no_rawat"]."'"), $nm_dokter,getKelasRS(),"","","#",$codernik,$baris["no_rawat"]);

                echo "<meta http-equiv='refresh' content='1;URL=?act=KlaimBaruManual&tahunawal=$tahunawal&bulanawal=$bulanawal&tanggalawal=$tanggalawal&tahunakhir=$tahunakhir&bulanakhir=$bulanakhir&tanggalakhir=$tanggalakhir&codernik=$codernik&action=no&keyword=$keyword'>";
            }if($action=="InputDiagnosa") {
                HapusAll("temppanggilnorawat");
                InsertData2("temppanggilnorawat","'$norawat'");
                echo "<meta http-equiv='refresh' content='1;URL=?act=KlaimBaruManual&tahunawal=$tahunawal&bulanawal=$bulanawal&tanggalawal=$tanggalawal&tahunakhir=$tahunakhir&bulanakhir=$bulanakhir&tanggalakhir=$tanggalakhir&codernik=$codernik&action=no&keyword=$keyword'>";
            }

            $BtnKeluar=isset($_POST['BtnKeluar'])?$_POST['BtnKeluar']:NULL;
            if (isset($BtnKeluar)) {
                echo"<meta http-equiv='refresh' content='1;URL=?act=KlaimBaruManual&action=Keluar'>";
            }

        ?>
        </div>
            <table width="100%" align="center" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr class="head3">					
                    <td width="720px">
                        Periode : 
                        <select name="tanggalawal" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi3'));" id="TxtIsi3">
                             <?php
                                if(!$tanggalawal==""){
                                    echo "<option id='TxtIsi3' value=$tanggalawal>$tanggalawal</option>";
                                }                                    
                                loadTglnow();
                             ?>
                        </select>                        
                        <select name="bulanawal" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi2'));" id="TxtIsi2">
                             <?php
                                if(!$bulanawal==""){
                                    echo "<option id='TxtIsi2' value=$bulanawal>$bulanawal</option>";
                                }                                    
                                loadBlnnow();
                             ?>
                        </select>                        
                        <select name="tahunawal" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi1'));" id="TxtIsi1">
                             <?php
                                if(!$tahunawal==""){
                                    echo "<option id='TxtIsi1' value=$tahunawal>$tahunawal</option>";
                                }                                    
                                loadThnnow();
                             ?>
                        </select>
                        &nbsp;s.d.&nbsp;
                        <select name="tanggalakhir" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi6'));" id="TxtIsi6">
                             <?php
                                if(!$tanggalakhir==""){
                                    echo "<option id='TxtIsi6' value=$tanggalakhir>$tanggalakhir</option>";
                                }                                    
                                loadTglnow();
                             ?>
                        </select>                        
                        <select name="bulanakhir" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi5'));" id="TxtIsi5">
                             <?php
                                if(!$bulanakhir==""){
                                    echo "<option id='TxtIsi5' value=$bulanakhir>$bulanakhir</option>";
                                }                                    
                                loadBlnnow();
                             ?>
                        </select>
                        <select name="tahunakhir" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi4'));" id="TxtIsi4">
                             <?php
                                if(!$tahunakhir==""){
                                    echo "<option id='TxtIsi4' value=$tahunakhir>$tahunakhir</option>";
                                }                                    
                                loadThnnow();
                             ?>
                        </select>
                        &nbsp;
                        Keyword : <input name="keyword" class="text" onkeydown="setDefault(this, document.getElementById('MsgIsi1'));" type=text id="TxtIsi1" value="<?php echo $keyword;?>" size="25" maxlength="200" pattern="[a-zA-Z0-9, ./@_]{1,200}" title=" a-zA-Z0-9, ./@_ (Maksimal 200 karakter)" autocomplete="off" autocomplete="off" autofocus />
                        <input name=BtnCari type=submit class="button" value="&nbsp;&nbsp;Cari&nbsp;&nbsp;" />
                    </td>
                    <td width="120px" >Record : <?php echo $jumlah; ?> </td>
                    <td><input name=BtnKeluar type=submit class="button" value="&nbsp;&nbsp;&nbsp;Keluar&nbsp;&nbsp;&nbsp;" /> </td>
                </tr>
            </table>
	</form>
    </div>
</div>
