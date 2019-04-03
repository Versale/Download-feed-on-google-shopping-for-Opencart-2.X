<?php
class ControllerProductExport extends Controller {
	private $error = array();

	public function index() {
		
	$xml = new DomDocument('1.0', 'utf-8'); //создаем новый экземпляр<

	$rss = $xml->appendChild($xml->createElement('rss')); // добавляем тег rss

	$rss->setAttribute('version', '2.0'); //атрибуты

	$rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');//атрибуты/

	$main_title = $rss->appendChild($xml->createElement('title'));

	$main_title->appendChild($xml->createTextNode('Фид данных Merchant Center'));

	$main_link = $rss->appendChild($xml->createElement('link'));

	$main_link->appendChild($xml->createTextNode('https://ukspar.ua/'));

	$main_desc = $rss->appendChild($xml->createElement('description'));

	$main_desc->appendChild($xml->createTextNode('Фид данных для загрузки в Merchant Center'));

	$items = $rss->appendChild($xml->createElement('channel'));

        $data['shopme_default_product_style'] = $this->config->get('shopme_default_product_style');

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$results = $this->model_catalog_product->getProducts();

		foreach ($results as $result) {

				if ($result['image']) {
					$image = 'https://ukspar.ua/image/' . $result['image'];
				}
  

			$href = $this->url->link('product/product', 'product_id=' . $result['product_id']);
  			$price_cost = round($result['price'], 2) . ' UAH';

			$category_info = $this->model_catalog_product->getCategories($result['product_id']);

			$product_type = 'Главная';
			foreach ($category_info as $category_info1) {
				$category_id = $category_info1['category_id'];
				$category_infos1 = $this->model_catalog_category->getCategory($category_id);
				$product_type .= ' > ' . $category_infos1['name'];
			}

			$replace = array("«", "»");
			$brand = str_replace($replace, '', $result['manufacturer']);
			$mpn = $result['model'];
  

			$item = $items->appendChild($xml->createElement('item'));

			$id = $item->appendChild($xml->createElement('g:id'));

			$id->appendChild($xml->createTextNode($result['product_id']));

			$title = $item->appendChild($xml->createElement('title'));

			$title->appendChild($xml->createTextNode($result['name']));

			$condition = $item->appendChild($xml->createElement('g:condition'));

			$condition->appendChild($xml->createTextNode('новый'));

			$url = $item->appendChild($xml->createElement('link'));

			$url->appendChild($xml->createTextNode($href));

			$price = $item->appendChild($xml->createElement('g:price'));

			$price->appendChild($xml->createTextNode($price_cost));

			$image_link = $item->appendChild($xml->createElement('g:image_link'));

			$image_link->appendChild($xml->createTextNode($image));

			$description = $item->appendChild($xml->createElement('g:description'));

			$description->appendChild($xml->createTextNode(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))));

			$availability = $item->appendChild($xml->createElement('g:availability'));

			$availability->appendChild($xml->createTextNode('В наличии'));

			$product_types = $item->appendChild($xml->createElement('g:product_type'));

			$product_types->appendChild($xml->createTextNode($product_type));

			$brands = $item->appendChild($xml->createElement('g:brand'));

			$brands->appendChild($xml->createTextNode($brand));

			$mpns = $item->appendChild($xml->createElement('g:mpn'));

			$mpns->appendChild($xml->createTextNode($mpn));

			}




			$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true

$xml->save('feed.xml');

			if($xml->save('feed.xml')) {

			echo 'Обновление фида завершилось успешно!';

			}else {

			echo "Не удалось сохранить файл фида данных. Возможно у файла не достаточно прав доступа";

			}

			
	}
}

?>
			<a href="feed.xml">здесь</a>


		
