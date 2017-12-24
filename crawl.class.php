        <?php

include_once "simple_html_dom.php";

class H_Crawl {

	var $html_content = '';
	var $arr_att_clean = array();

	function __construct() {
		// nothing to do
	}

	// lay html
	function getHtml($link) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $link);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1');
		$query = curl_exec($curl_handle);
		curl_close($curl_handle);
		return $query;
	}

	// function lay title cua bai viet
	function getTitle($content, $att_title) {
		$html = str_get_html($content);
		foreach ($html->find($att_title) as $e) {
			$title = $e->innertext;
		}
		return $title;
	}

	// function lay noi dung cua mot bai viet
	function getContent($content, $att_content) {
		$html = str_get_html($content);
		foreach ($html->find($att_content) as $e) {
			$content_html = $e->innertext;
		}
		$html = str_get_html($content_html);

		foreach ($this->arr_att_clean as $att_clean) {
			// google+
			foreach ($html->find($att_clean) as $e) {
				$e->outertext = '';
			}
		}

		$ret = $html->save();
		return $ret;
	}

	// get link
	function getLink($content, $att_content) {
		$html = str_get_html($content);

		// link content
		$link = [];
		foreach ($html->find($att_content) as $e) {
			$link[] = $e->href;
		}
		return $link;
	}

	// function remove het cac lien ket trong mot chuoi html truyen vao
	function removeLink($content) {
		$html = str_get_html($content);
		// link content
		foreach ($html->find('a') as $e) {
			$e->outertext = $e->innertext;
		}
		$ret = $html->save();
		return $ret;
	}

	// function xoa phan tu html cuoi cung
	function removeLastElement($content, $element) {
		$html = str_get_html($content);
		// link content
		$html->find($element, -1)->outertext = '';
		$ret = $html->save();
		return $ret;
	}

	// function xoa phan tu html truyen vao dau tien
	function removeFirstElement($content, $element) {
		$html = str_get_html($content);
		$html->find($element, 0)->outertext = '';
		$ret = $html->save();
		return $ret;
	}

}
