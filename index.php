<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Çoklu Veri Silme</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script src="deleteall.js"></script>

	<?php
	include('fonc.php'); // Veritabanımızı sayfamıza dahil ediyoruz

	if (isset($_POST['delete'])) { // Silme Onay Kutusunun Işaretli olup olmadığını kontrol etme	
		$selecteddata = implode(', ', $_POST['delete']);    // Checkbox ile birlikte gelen "$selecteddata" değişkenine iletiyoruz
		$query = $connect->prepare('select * FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // Kimlikleri veritabanından alıyoruz
		$query->execute(); //Sorguyu yürütme ve veri alma

		while ($result = $query->fetch()) { // Gelen veriler bir while döngüsü ile döndürülür

        @unlink('img/' . $result["photo"]);// Eski dosyalar (fotoğraflar) silindi. isteğe bağlı olarak, bu kodu kullanamazsınız
    }

    $query = $connect->prepare('DELETE FROM `blog` WHERE `id` IN (' . $selecteddata . ')'); // Gelen veri kimliklerine göre silme sorgumuz
    $query->execute(); // Çalışan Sorgu

    if ($query) { // Sorgumuz işe yaradıysa, index.php sayfamıza yönlendiriyoruz
    	header("location:index.php");
    }

}
?>
</head> 

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- Tablomuza bir Form ekliyoruz, bu bir Form olmalı, aksi takdirde veriler yayınlanmaz, çalışmaz. -->
				<form action="" method="post">
					<!-- Our table-->
					<table class="table table-hover">

						<!-- Seçilen Sil Düğmemizi Modal ile Uyarı vermek için yapıyoruz.-->

						<a class="btn btn-danger font-18" href="#" data-toggle="modal"
						data-target="#deleteall"><i class="fa fa-trash"> Seçilen Verileri Sil</i></a>
						<!-- Logout Modal-->
						<div class="modal fade" id="deleteall" tabindex="-1" role="dialog"
						aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Tüm Eylemleri Sil</h5>
									<button class="close" type="button" data-dismiss="modal"
									aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<h1 class="text-center">Önemli Uyarı!</h1>
								<h3 class="text-center">Seçilen veriler silinecektir. Onaylıyor musunuz</h3></div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-danger font-18 "><i class="fa fa-trash"> Seçilenleri sil</i></button>
									<button class="btn btn-secondary" type="button"
									data-dismiss="modal">Iptal etmek
								</button>

							</div>
						</div>
					</div>
				</div>    <!-- Seçilen modu silmemizin sonu-->


				<thead class="thead-dark">
					<th>     
						<!--Masamızın En Üstündeki Tümünü Seç Onay kutumuzu tablomuzun thead kısmına yerleştiriyoruz-->
						<div class="checkbox">
							<input type="checkbox" id="selectall" value=""> <!--Onay Kutumuza bir id veriyoruz ve hepsini javascript kodlarıyla seçiyoruz. -->
						</div>
					</th>
					<th>ID</th>
					<th>foto</th>
					<th>Başlık</th>
					<th>Açıklama</th>
				</thead>
				<?php
				$query = $connect->prepare("SELECT * FROM blog"); // Veritabanından veri alma
				$query->execute();  // Sorgumuzu Çalıştırıyoruz

				while ($result=$query->fetch())  // Veritabanındaki veriler While Loop ile döndürülür.

				{  // Başlangıçta verilerimiz var

					?>

					<tbody>
						<td>
							 <!--Silinen Datayı tanımlamak için Checkbox'ı kullanıyoruz-->
							<div class="checkbox">
								<input class="chck" type="checkbox" name="delete[]" 
								value="<?php echo $result['id']; ?>"><!--Çoklu silme sorgumuz için tanınacak onay kutusunun "name" kısmı için bir ad belirliyoruz.-->
								<label for="<?php echo $result['id']; ?>"></label>  <!--id yi veritabanından Checkbox'a gönderiyoruz-->
							</div>
						</td>
						<td><?= $result['id']?></td>
						<th><img src="img/<?= $result["photo"] ?>" width="150px"/></th>
						<td><?= $result['title']?></td>
						<td><?= $result['content']?></td>						
					</tbody>
					<?php 
				}  // Süre Sonu ile verilerimizi çekeriz ve sorgumuz sona erer.

				?>

			</table>
		</form>
	</div>
</div>
</div>

 <!--"hepsini sil" onay kutusuna tıkladığımızda, tüm onay kutularının işaretli olması için javascript kodumuzu ekliyoruz-->
<script type="text/javascript">
	$(document).ready(function () {
		$('#selectall').on('click', function () {
			if ($('#selectall:checked').length == $('#selectall').length) {
				$('input.chck:checkbox').prop('checked', true);
			} else {
				$('input.chck:checkbox').prop('checked', false);

			}
		});
	});
</script>
<script src="js/jquery-3.4.1.min.js"></script>	
<script src="js/bootstrap.min.js"></script>
</body>
</html>
