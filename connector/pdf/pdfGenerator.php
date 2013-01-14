<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
*/

class schedulerPDF {

	public $offsetTop = 15;
	public $offsetBottom = 10;
	public $offsetLeft = 10;
	public $offsetRight = 10;
	
	// header height of day container in month mode
	public $monthDayHeaderHeight = 6;
	// header height in month mode
	public $monthHeaderHeight = 8;
	// height of month name container in year mode
	public $yearMonthHeaderHeight = 8;
	// height of row in agenda mode
	public $agendaRowHeight = 6;
	// height of header in day and week mode
	public $dayTopHeight = 6;
	// width of left scale in day and week mode
	public $dayLeftWidth = 16;
	
	// font size settings
	public $monthHeaderFontSize = 9;
	public $monthDayHeaderFontSize = 8;
	public $monthEventFontSize = 7;
	public $yearHeaderFontSize = 8;
	public $yearFontSize = 8;
	public $agendaFontSize = 8;
	public $dayHeaderFontSize = 7;
	public $dayScaleFontSize = 8;
	public $dayEventHeaderFontSize = 7;
	public $dayEventBodyFontSize = 7;
	public $todayFontSize = 11;

	private $profile = 'color';
	private $orientation = 'P';
	private $mode;
	private $today;

	public $lineColor;
	public $bgColor;
	public $dayHeaderColor;
	public $dayBodyColor;
	public $dayHeaderColorInactive;
	public $dayBodyColorInactive;
	public $headerTextColor;
	public $textColor;
	public $eventTextColor;
	public $eventBorderColor;
	public $eventColor;
	public $todayTextColor;
	public $scaleColorOne;
	public $scaleColorTwo;

	public function printScheduler(&$xml) {
		$this->renderData($xml);
		$this->renderScales($xml);
		$this->renderEvents($xml);

		$this->wrapper = new pdfWrapper($this->offsetTop, $this->offsetRight, $this->offsetBottom, $this->offsetLeft, $this->orientation);
		switch ($this->mode) {
			case 'month':
				$this->orientation = 'L';
				$this->printMonth();
				break;
			case 'day':
			case 'week':
				$this->orientation = 'P';
				$this->printDayWeek();
				break;
			case 'year':
				$this->orientation = 'L';
				$this->printYear();
				break;
			case 'agenda':
				$this->orientation = 'P';
				$this->printAgenda();
				break;
			default:
				return false;
		}

//		$this->wrapper = '';
//		echo "<pre>";
//		print_r($this);
//		echo "</pre>";
		
		$this->wrapper->pdfOut();
	}

	// parses options profile, orientation, header and footer from xml 
	private function renderData($data) {
		if (isset($data->attributes()->profile)) {
			$this->profile = (string) $data->attributes()->profile;
		}
		if (isset($data->attributes()->orientation)) {
			$this->orientation = (string) $data->attributes()->orientation;
		}
		if (isset($data->attributes()->header)) {
			$this->header = (string) $data->attributes()->header;
		}
		if (isset($data->attributes()->footer)) {
			$this->footer = (string) $data->attributes()->footer;
		}
		$this->setProfile();
	}


	// sets color profile
	private function setProfile() {
		switch ($this->profile) {
			case 'color':
				$this->lineColor = '586A7E';
				$this->bgColor = 'C2D5FC';
				$this->dayHeaderColor = 'EBEFF4';
				$this->dayBodyColor = 'FFFFFF';
				$this->dayHeaderColorInactive = 'E2E3E6';
				$this->dayBodyColorInactive = 'ECECEC';
				$this->headerTextColor = '2F3A48';
				$this->textColor = '2F3A48';
				$this->eventTextColor = '887A2E';
				$this->eventBorderColor = 'B7A543';
				$this->eventColor = 'FFE763';
				$this->todayTextColor = '000000';
				$this->scaleColorOne = 'FCFEFC';
				$this->scaleColorTwo = 'DCE6F4';
				$this->yearDayColor = 'EBEFF4';
				$this->yearDayColorInactive = 'd6d6d6';
				break;
			case 'gray':
				$this->lineColor = '666666';
				$this->bgColor = 'D3D3D3';
				$this->dayHeaderColor = 'EEEEEE';
				$this->dayBodyColor = 'FFFFFF';
				$this->dayHeaderColorInactive = 'E3E3E3';
				$this->dayBodyColorInactive = 'ECECEC';
				$this->headerTextColor = '383838';
				$this->textColor = '000000';
				$this->eventTextColor = '000000';
				$this->eventBorderColor = '9F9F9F';
				$this->eventColor = 'DFDFDF';
				$this->todayTextColor = '000000';
				$this->scaleColorOne = 'E4E4E4';
				$this->scaleColorTwo = 'FDFDFD';
				$this->yearDayColor = 'EBEFF4';
				$this->yearDayColorInactive = 'E2E3E6';
				break;
			case 'bw':
				$this->lineColor = '000000';
				$this->bgColor = 'FFFFFF';
				$this->dayHeaderColor = 'FFFFFF';
				$this->dayBodyColor = 'FFFFFF';
				$this->dayHeaderColorInactive = 'FFFFFF';
				$this->dayBodyColorInactive = 'FFFFFF';
				$this->headerTextColor = '000000';
				$this->textColor = '000000';
				$this->eventTextColor = '000000';
				$this->eventBorderColor = '000000';
				$this->eventColor = 'FFFFFF';
				$this->todayTextColor = '000000';
				$this->scaleColorOne = 'FFFFFF';
				$this->scaleColorTwo = 'FFFFFF';	
				$this->yearDayColor = 'FFFFFF';
				$this->yearDayColorInactive = 'FFFFFF';
				break;
		}
	}


	// render scales
	private function renderScales($xml) {
		$scales = $xml->scale;
		$this->mode = (string) $scales->attributes()->mode;
		$this->today = (string) $scales->attributes()->today;

		switch ($this->mode) {
			case 'month':
				foreach ($scales->x->column as $text) {
					$this->topScales[] = (string) $text;
				}
				foreach ($scales->row as $text) {
					$week = explode("|", $text);
					$this->dayHeader[] = $week;
				}
				break;
				
			case 'year':
				foreach ($scales->month as $month) {
					$monthArr = Array();
					$monthArr['label'] = (string) $month->attributes()->label;
					foreach ($month->column as $col) {
						$monthArr['columns'][] = (string) $col;
					}
					
					foreach ($month->row as $row) {
						$row = explode("|", $row);
						$monthArr['rows'][] = $row;
					}
					$this->yearValues[] = $monthArr;
				}
				break;
			
			case 'agenda':
				$this->agendaHeader[0] = (string) $scales->column[0];
				$this->agendaHeader[1] = (string) $scales->column[1];
				break;

			case 'day':
			case 'week':
				foreach ($scales->x->column as $col) {
					$this->columnHeader[] = (string) $col;
				}
				foreach ($scales->y->row as $row) {
					$this->rowHeader[] = (string) $row;
				}
			default:
				return false;
		}
	}


	// parses events from xml
	private function renderEvents($xml) {
		$this->events = Array();
		switch ($this->mode) {
			case 'month':
				foreach ($xml->event as $ev) {
					$event['week'] = (int) $ev->attributes()->week - 1;
					$event['day'] = (int) $ev->attributes()->day;
					$event['x'] = (int) $ev->attributes()->x;
					$event['y'] = (int) $ev->attributes()->y;
					$event['width'] = (int) $ev->attributes()->width;
					$event['height'] = (int) $ev->attributes()->height;
					$event['type'] = (string) $ev->attributes()->type;
					$event['text'] = (string) $ev->body;
					$event['color'] = (string) $ev->body->attributes()->color;
					$this->events[] = $event;
				}
				break;
			case 'year':
				foreach ($xml->event as $ev) {
					$week = (int) $ev->attributes()->week;
					$day = (int) $ev->attributes()->day;
					$month = (int) $ev->attributes()->month;
					$this->events[] = $month.'_'.$week.'_'.$day;
				}
				break;
			case 'agenda':
				foreach ($xml->event as $ev) {
					$head = (string) $ev->head;
					$body = (string) $ev->body;
					$this->events[] = Array('head' => $head, 'body' => $body);
				}
				break;
			case 'day':
			case 'week':
				foreach ($xml->event as $ev) {
					$event['head'] = (string) $ev->header;
					$event['body'] = (string) $ev->body;
					$event['x'] = (int) $ev->attributes()->x;
					$event['y'] = (int) $ev->attributes()->y;
					$event['width'] = (int) $ev->attributes()->width;
					$event['height'] = (int) $ev->attributes()->height;
					$event['color'] = (string) $ev->body->attributes()->color;
					$this->events[] = $event;
				}
		}
	}


	private function printMonth() {
		$this->wrapper->drawMonthHeader($this->orientation, $this->topScales, $this->monthHeaderHeight, $this->bgColor, $this->lineColor, $this->monthHeaderFontSize);
		$this->wrapper->drawToday($this->today, $this->todayFontSize);
		$this->wrapper->drawMonthGrid($this->dayHeader, $this->monthDayHeaderHeight, $this->dayHeaderColor, $this->dayBodyColor, $this->dayHeaderColorInactive, $this->dayBodyColorInactive, $this->monthDayHeaderFontSize);
		$this->wrapper->drawMonthEvents($this->events, $this->eventColor, $this->eventBorderColor, $this->monthEventFontSize, $this->profile);
	}


	private function printYear() {
		$this->wrapper->drawYear($this->orientation, $this->yearValues, $this->events, $this->yearMonthHeaderHeight, $this->bgColor, $this->lineColor, $this->yearDayColor, $this->yearDayColorInactive, $this->eventColor, $this->yearHeaderFontSize, $this->yearFontSize);
		$this->wrapper->drawToday($this->today, $this->todayFontSize);
	}


	private function printAgenda() {
		$this->wrapper->drawAgenda($this->agendaHeader, $this->events, $this->agendaRowHeight, $this->bgColor, $this->lineColor, $this->scaleColorOne, $this->scaleColorTwo, $this->agendaFontSize, $this->today, $this->todayFontSize);
	}


	private function printDayWeek() {
		$this->wrapper->drawDayWeekHeader($this->columnHeader, $this->rowHeader, $this->dayTopHeight, $this->dayLeftWidth, $this->bgColor, $this->lineColor, $this->scaleColorOne, $this->scaleColorTwo, $this->dayHeaderFontSize, $this->dayScaleFontSize, $this->profile);
		$this->wrapper->drawToday($this->today, $this->todayFontSize);
		$this->wrapper->drawDayWeekEvents($this->events, $this->eventColor, $this->eventBorderColor, $this->dayEventHeaderFontSize, $this->dayEventBodyFontSize, $this->profile);
	}

}


?>