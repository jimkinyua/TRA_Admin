<?php

	require('razorflow_php/razorflow.php');
	require 'DB_PARAMS/rezor_connect.php';
	
	class SampleDashboard extends StandaloneDashboard 
	{
		protected $pdo;
		public function initialize()
		{
			//$this->pdo = new PDO("databases/Sqlserver");
			$this->pdo = new DATABASE_CONFIG;
		}
		private function getTopArtists () 
		{
			$sql="select TOP 20 sc.CategoryName, count(s.ServiceID) Total_Services from ServiceGroup sg
			join ServiceCategory sc on sc.ServiceGroupID=sg.ServiceGroupID
			join Services s on s.ServiceCategoryID=sc.ServiceCategoryID
			group by sc.CategoryName 
			order by COUNT(s.ServiceID) DESC";
			
			//$query = $this->pdo->query("SELECT SUM(Track.UnitPrice * Quantity) AS total_sales, Artist.Name FROM InvoiceLine JOIN Invoice ON Invoice.InvoiceId = InvoiceLine.InvoiceId JOIN Track ON Track.TrackId = InvoiceLine.TrackId JOIN Album ON Track.AlbumId = Album.AlbumId JOIN Artist ON Album.ArtistId = Artist.ArtistId GROUP BY Artist.Name ORDER BY total_sales DESC LIMIT 5;");
			$query = $this->pdo->query($sql);
		  return  $query->fetchAll(PDO::FETCH_ASSOC);
		}
		/* private function getTopAlbums($artistName = null) 
		{
			if($artistName !== null) 
			{
				$res = $this->pdo->prepare("SELECT SUM(Track.UnitPrice  Quantity) AS total_sales, Album.Title FROM InvoiceLine JOIN Invoice ON Invoice.InvoiceId = InvoiceLine.InvoiceId JOIN Track ON Track.TrackId = InvoiceLine.TrackId JOIN Album ON Track.AlbumId = Album.AlbumId JOIN Artist ON Album.ArtistId = Artist.ArtistId WHERE Artist.Name = :artistName GROUP BY Album.Title ORDER BY total_sales DESC LIMIT 5;");
				$res->execute(array('artistName' => $artistName));
			}
			else 
			{
			  $res = $this->pdo->query("SELECT SUM(Track.UnitPrice  Quantity) AS total_sales, Album.Title FROM InvoiceLine JOIN Invoice ON Invoice.InvoiceId = InvoiceLine.InvoiceId JOIN Track ON Track.TrackId = InvoiceLine.TrackId JOIN Album ON Track.AlbumId = Album.AlbumId JOIN Artist ON Album.ArtistId = Artist.ArtistId GROUP BY Album.Title ORDER BY total_sales DESC LIMIT 5;");
			}
		  return  $res->fetchAll(PDO::FETCH_ASSOC);
		}*/
		public function buildDashboard () 
		{
			$this->setDashboardTitle ("SQL Demo Dashboard");
			$topArtistsChart = new ChartComponent ('c1');
			$topArtistsChart->setDimensions (6, 6);
			$topArtistsChart->setCaption ("Services Per Category");
			$top_artists = $this->getTopArtists();
			$topArtistsChart->setLabels (ArrayUtils::pluck($top_artists, "CategoryName"));
			$topArtistsChart->addSeries ('drink', "DRINK", ArrayUtils::pluck($top_artists, "Total_Services"), array(
			'seriesStacked' => true,
			'seriesDisplayType' => "column"
			));

			$topArtistsChart->addSeries ('food', "FOOD", ArrayUtils::pluck($top_artists, "Total_Services"), array(
			  'seriesStacked' => true,
			  'seriesDisplayType' => "column"
			));
			  $this->addComponent($topArtistsChart);
			$topAlbumsChart = new ChartComponent ('c2');
			$topAlbumsChart->setDimensions (6, 6);
			$topAlbumsChart->setCaption ("Top 5 Albums by Revenue");
			$top_albums = $this->getTopAlbums();
			$topAlbumsChart->setLabels (ArrayUtils::pluck($top_albums, "Title"));
			$topAlbumsChart->addSeries ('top_albums', "Top Albums", ArrayUtils::pluck($top_albums, "Total_Services"));
			$this->addComponent($topAlbumsChart);

			$topArtistsChart->bindToEvent ("itemClick", array($topAlbumsChart), "handleArtistChartClick", $this);
		} 
		public function handleArtistChartClick ($source, $target, $params) 
		{
			$artistName = $params['label'];
			$topAlbumsChart = $this->getComponentByID("c2");
			$top_albums = $this->getTopAlbums ($artistName);
			$topAlbumsChart->setCaption ("Top 5 albums by ".$artistName);
			$topAlbumsChart->clearChart();
			$topAlbumsChart->setLabels (ArrayUtils::pluck($top_albums, "Title"));
			$topAlbumsChart->addSeries('top_albums', "Top Albums", ArrayUtils::pluck($top_albums, "Total_Services"));
		}
	}
$db = new SampleDashboard();
$db->renderStandalone();