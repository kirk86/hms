<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
*/


require_once 'tcpdf_ext.php';

class pdfWrapper {
	private $cb;
	private $footenotes;
	private $colWidth = 50; // column width in month mode for footenotes
	private $colOffset = 5;  // spaces between columns in month mode for footenotes

	public function pdfWrapper($offsetTop, $offsetRight, $offsetBottom, $offsetLeft) {
		$this->cb = new TCPDFExt('P', 'mm', 'A4', true, 'UTF-8', false);

		$this->offsetLeft = $offsetLeft;
		$this->offsetRight = $offsetRight;
		$this->offsetTop = $offsetTop;
		$this->offsetBottom = $offsetBottom;

		// sets header and footer
		$this->cb->setPrintHeader(false);
		$this->cb->setPrintFooter(false);
		$this->cb->SetMargins($this->offsetLeft, $this->offsetTop, $this->offsetRight);
		$this->cb->SetAutoPageBreak(FALSE, $this->offsetBottom);
		$this->cb->SetFooterMargin($this->offsetBottom);

		// sets output PDF information
		$this->cb->SetCreator('DHTMLX LTD');
		$this->cb->SetAuthor('DHTMLX LTD');
		$this->cb->SetTitle('dhtmlxScheduler');
		$this->cb->SetSubject('DHTMLX LTD');
		$this->cb->SetKeywords('');

		// sets font family and size
		$this->cb->SetFont('freesans', '', 8);
	}


	// draws scheduler header in month mode
	public function drawMonthHeader($orientation, $topScale, $topScaleHeight, $bgColor, $lineColor, $monthHeaderFontSize) {
		$this->cb->AddPage($orientation);
		$this->bgColor = $this->convertColor($bgColor);
		$this->lineColor = $this->convertColor($lineColor);
		$this->topScaleHeight = $topScaleHeight;
		$this->topScale = $topScale;

		$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2', 'color' => $this->lineColor);
		$this->cb->SetLineStyle($this->lineStyle);
		$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);
		$this->cb->SetFontSize($monthHeaderFontSize);

		// calculates day width
		$this->dayWidth = ($this->cb->getPageWidth() - $this->offsetLeft - $this->offsetRight)/7;
		// circle: for every column of header draw cell
		for ($i = 0; $i < count($topScale); $i++) {
			$this->cb->Cell($this->dayWidth, $topScaleHeight, $topScale[$i], 1, 0, 'C', 1);
		}
	}


	// draws scheduler grid on month mode
	public function drawMonthGrid($dayHeader, $dayHeaderHeight, $dayHeaderColor, $dayBodyColor, $dayHeaderColorInactive, $dayBodyColorInactive, $monthDayHeaderFontSize) {
		// sets starting coordinates
		$this->cb->setX($this->offsetLeft);
		$this->cb->setY($this->offsetTop + $this->topScaleHeight, false);

		$this->dayHeaderColor = $this->convertColor($dayHeaderColor);
		$this->dayBodyColor = $this->convertColor($dayBodyColor);
		$this->dayHeaderColorInactive = $this->convertColor($dayHeaderColorInactive);
		$this->dayBodyColorInactive = $this->convertColor($dayBodyColorInactive);

		// calculates height of day container header, body and whole height of day container
		$this->dayHeaderHeight = $dayHeaderHeight;
		$this->dayHeader = $dayHeader;
		$this->dayBodyHeight = ($this->cb->getPageHeight() - $this->offsetTop - $this->offsetBottom - $this->topScaleHeight - $this->dayHeaderHeight*count($dayHeader))/count($dayHeader);
		$this->dayHeight = $this->dayHeaderHeight + $this->dayBodyHeight;

		// sets font size
		$this->cb->SetFontSize($monthDayHeaderFontSize);
		// creates array of values where result['week']['day'] is true if day container in current month and false otherwise
		$activeDays = $this->setActiveDays($dayHeader);

		// circle for drawing day containers
		for ($i = 0; $i < count($dayHeader); $i++) {
			// draws day headers for every cell of row
			for ($j = 0; $j < count($dayHeader[$i]); $j++) {
				$ln = ($j == 6) ? 1 : 0;
				$color = ($activeDays[$i][$j] == true) ? $this->dayHeaderColor : $this->dayHeaderColorInactive;
				$this->cb->SetFillColor($color['R'], $color['G'], $color['B']);
				$this->cb->Cell($this->dayWidth, $this->dayHeaderHeight, $dayHeader[$i][$j], 1, $ln, 'R', 1);
				$this->dayEventsHeight[$i][$j] = 0;
			}
			// draws day body for every cell of row
			for ($j = 0; $j < 7; $j++) {
				$ln = ($j == 6) ? 1 : 0;
				$color = ($activeDays[$i][$j] == true) ? $this->dayBodyColor : $this->dayBodyColorInactive;
				$this->cb->SetFillColor($color['R'], $color['G'], $color['B']);
				$this->cb->Cell($this->dayWidth, $this->dayBodyHeight, '', 1, $ln, 'R', 1);
			}
		}
	}


	// draws events in month mode
	public function drawMonthEvents($events, $eventColor, $eventBorderColor, $monthEventFontSize, $mode, $offset = 1, $offsetLine = 6) {
		$this->eventColor = $this->convertColor($eventColor);
		$this->eventBorderColor = $this->convertColor($eventBorderColor);
		$this->events = $events;

		$this->cb->setFontSize($monthEventFontSize);

		$this->cb->SetFillColor($this->eventColor['R'], $this->eventColor['G'], $this->eventColor['B']);		
		// initial value for footnote number
		$footNum = 0;
		// initial values for checking if in some day footenote has already drawed
		$day = -1;
		$week = -1;

		// circle for drawing every event
		for ($i = 0; $i < count($this->events); $i++) {
			$event = $this->events[$i];
			if ($event['week'] >= count($this->dayHeader)) {
				continue;
			}
			// calculation x-, y- positions, width and height of event
			$x = $this->offsetLeft + $event['day']*$this->dayWidth;
			$y = $this->offsetTop + $this->topScaleHeight + $event['week']*$this->dayHeight + $this->dayHeaderHeight + $this->dayEventsHeight[$event['week']][$event['day']] + $offset;
			if ($event['type'] == 'event_line') {
				$width = $event['width']*$this->dayWidth/100;
				$x += 1.5;
			} else {
				$width = $this->dayWidth - 1;
			}
			$height = 4;

			$this->cb->setX($x);
			$this->cb->setY($y, false);

			// checks if event can be drawed in day container
			if ($y + $height > ($this->offsetTop + $this->topScaleHeight + ($event['week'] + 1)*$this->dayHeight)) {
				// checks if footenote hasn't already drawed for current day and week values
				if (!(($week == $event['week'])&&($day == $event['day']))) {
					$footNum++;
					$x1Line = $this->offsetLeft + $this->dayWidth*($event['day'] + 1);
					$x2Line = $x1Line - $offsetLine;
					$y1Line = $this->offsetTop + $this->topScaleHeight + $this->dayHeight*($event['week'] + 1) - $offsetLine;
					$y2Line = $this->offsetTop + $this->topScaleHeight + $this->dayHeight*($event['week'] + 1);
					$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2', 'color' => $this->lineColor);
					$this->cb->SetLineStyle($this->lineStyle);
					$this->cb->Line($x1Line, $y1Line, $x2Line, $y2Line);
					$textWidth = $this->cb->getStringWidth($footNum);
					$xText = $x1Line - $textWidth - 0.5;
					$yText = $y2Line - 0.6;

					$day = $event['day'];
					$week = $event['week'];
					$this->cb->Text($xText, $yText, $footNum);
				}
				// sets variable which means that this event will print in footenotes
				$this->events[$i]['foot'] = true;
			} else {
				// draws event in current day
				$this->lineStyle = Array('width' => 0.3, 'cap' => 'round', 'join' => 'round', 'dash' => '0', 'color' => $this->eventBorderColor);
				$this->cb->SetLineStyle($this->lineStyle);
				$text = $this->textWrap($event['text'], $width, $monthEventFontSize);

				// sets event color
				$color = $this->processEventColor($event['color']);
				if (($color == 'transparent')||($mode !== 'color')) {
					$this->cb->SetFillColor($this->eventColor['R'], $this->eventColor['G'], $this->eventColor['B']);
				} else {
					$color = $this->convertColor($color);
					$this->cb->SetFillColor($color['R'], $color['G'], $color['B']);
				}

				if ($event['type'] == 'event_clear') {
					$this->cb->Cell($width, $height, $text, 0, 0, 'L', 0);
				} else {
					$this->cb->Cell($width, $height, $text, 1, 0, 'L', 1);
				}
				for ($j = $event['day']; ($j < $event['day'] + ceil($width/$this->dayWidth))&&($j < 7); $j++) {
					if ($event['week'] < count($this->dayHeader)) {
						$this->dayEventsHeight[$event['week']][$j] += $height + $offset;
					}
				}
				$this->events[$i]['foot'] = false;
			}
		}
		// draws footenotes
		if ($footNum > 0) {
			$this->drawMonthFootenotes();
		}
	}


	// draws pages with events which can not being showed in day container
	private function drawMonthFootenotes() {
		$this->cb->addPage();

		// initials x-, y- coordinates
		$x = $this->offsetLeft;
		$y = $this->offsetTop;
		
		// variables for checking if events have the same date
		$week = -1;
		$day = -1;
		$footNum = 1;
		// circle for all events
		for ($i = 0; $i < count($this->events); $i++) {
			// checks not printed events
			if (($this->events[$i]['week'] < count($this->dayHeader))&&($this->events[$i]['foot'] == true)) {
				$event = $this->events[$i];
				$text = $event['text'];
				// checks if it's necessary to print footenote number
				if (!(($week == $event['week'])&&($day == $event['day']))) {
					$textTop = $this->dayHeader[$event['week']][$event['day']].'['.$footNum.']';
					$linesNum = $this->cb->getNumLines($text, $this->colWidth);
					$heightEvent = $linesNum*$this->cb->getFontSize() + $this->cb->getFontSize()*0.5*($linesNum + 1);
					$linesNum = $this->cb->getNumLines($textTop, $this->colWidth);
					$heightHeader = $linesNum*$this->cb->getFontSize() + $this->cb->getFontSize()*0.5*($linesNum + 1);
					// checks if the current column is over
					if ($y + $heightEvent + $heightHeader > $this->cb->getPageHeight() - $this->offsetBottom) {
						$x += $this->colWidth + $this->colOffset;
						$y = $this->offsetTop;
						// checks if it's necessary to add new page
						if ($x + $this->colWidth > $this->cb->getPageWidth() - $this->offsetRight) {
							$this->cb->addPage();
							$x = $this->offsetLeft;
							$y = $this->offsetTop;
						}
					}
					
					$this->cb->MultiCell($this->colWidth, 5, $textTop, 0, 'C', 0, 0, $x, $y);
					$y += $this->cb->getLastH();
					$footNum++;
				}

				// checks if currrent column is over
				if ($y + $heightEvent > $this->cb->getPageHeight() - $this->offsetBottom) {
					$x += $this->colWidth + $this->colOffset;
					$y = $this->offsetTop;
					// checks if it's necessary to add new page
					if ($x + $this->colWidth > $this->cb->getPageWidth() - $this->offsetRight) {
						$this->cb->addPage();
						$x = $this->offsetLeft;
						$y = $this->offsetTop;
					}
					$textTop = $this->dayHeader[$event['week']][$event['day']].'['.($footNum - 1).']';
					$this->cb->MultiCell($this->colWidth, 5, $textTop, 0, 'C', 0, 0, $x, $y);
					$y += $this->cb->getLastH();
				}
				// draws event
				$this->cb->MultiCell($this->colWidth, 5, $text, 1, 'L', 1, 0, $x, $y);
				$y += $this->cb->getLastH();
				$week = $event['week'];
				$day = $event['day'];
			}
		}
	}


	// creates array with active/inactive option for every day
	private function setActiveDays($dayHeader) {
		if ($dayHeader[0][0] == '1') {
			// month grid starts from first day of month
			$flag = true;
			$flagCount = 1;
		} else {
			// month grid starts from day of previous month
			$flag = false;
			$flagCount = 0;
		}
		$activeDays = Array();
		$prevDay = (int) $dayHeader[0][0];
		
		for ($i = 0; $i < count($dayHeader); $i++) {
			for ($j = 0; $j < count($dayHeader[$i]); $j++) {
				// check if previous day value is less then current day value
				if (((int) $dayHeader[$i][$j] < $prevDay)&&($flagCount < 2)) {
					$flag = !$flag;
					$flagCount++;
				}
				$activeDays[$i][$j] = $flag;
				$prevDay = (int) $dayHeader[$i][$j];
			}
		}
		return $activeDays;
	}
	
	
	//
	public function drawYear($orientation, $yearValues, $events, $headerHeight, $bgColor, $lineColor, $dayColor, $dayColorInactive, $eventColor, $yearHeaderFontSize, $yearFontSize) {
		$this->cb->AddPage($orientation);
		$this->bgColor = $this->convertColor($bgColor);
		$this->lineColor = $this->convertColor($lineColor);
		$this->dayColor = $this->convertColor($dayColor);
		$this->dayColorInactive = $this->convertColor($dayColorInactive);
		$this->eventColor = $this->convertColor($eventColor);
		$this->yearValues = $yearValues;
		$this->events = $events;
		$this->headerHeight = $headerHeight;

		// offset between monthes
		$offset = 5;

		// sets line style and color
		$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2', 'color' => $this->lineColor);
		$this->cb->SetLineStyle($this->lineStyle);
		$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);

		// calculates month width and height
		$monthWidth = ($this->cb->getPageWidth() - $this->offsetLeft - $this->offsetRight - $offset*3)/4;
		$monthHeight = ($this->cb->getPageHeight() - $this->offsetTop - $this->offsetBottom - $offset*2)/3;

		// circles for drawing all monthes
		for ($i = 0; $i < 3; $i++) {
			for ($j = 0; $j < 4; $j++) {
				$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);
				$num = $i*4 + $j;
				// calculates x- and y-coordinates of current month
				$x = $this->offsetLeft + $j*$monthWidth + $offset*($j);
				$y = $this->offsetTop + $i*$monthHeight + $offset*($i);
				$this->cb->setX($x);
				$this->cb->setY($y, false);
				$this->cb->SetFontSize($yearHeaderFontSize);
				// draws name of month
				$this->cb->Cell($monthWidth, $this->headerHeight, $this->yearValues[$num]['label'], 1, 1, 'C', 1);
				// calculates day width and height
				$dayWidth = $monthWidth/7;
				$dayHeight = ($monthHeight - $headerHeight)/7;
				$y += $headerHeight;
				// create array of day in current month
				$activeDays = $this->setActiveDays($this->yearValues[$num]['rows']);

				// draws day names
				for ($k = 0; $k < count($this->yearValues[$num]['columns']); $k++) {
					$this->cb->setX($x);
					$this->cb->setY($y, false);
					$this->cb->Cell($dayWidth, $dayHeight, $this->yearValues[$num]['columns'][$k], 1, 1, 'C', 1);
					$x += $dayWidth;
				}
				$y += $dayHeight;
				$x -= $dayWidth*7;
				$this->cb->SetFontSize($yearFontSize);
				// draws days
				for ($k = 0; $k < count($this->yearValues[$num]['rows']); $k++) {
					for ($l = 0; $l < count($this->yearValues[$num]['rows'][$k]); $l++) {
						$this->cb->setX($x);
						$this->cb->setY($y, false);
						// checks if day of current month
						if ($activeDays[$k][$l] == true) {
							// checks if the day have events
							if (in_array($num.'_'.$k.'_'.$l, $this->events)) {
								$this->cb->SetFillColor($this->eventColor['R'], $this->eventColor['G'], $this->eventColor['B']);
							} else {
								$this->cb->SetFillColor($this->dayColor['R'], $this->dayColor['G'], $this->dayColor['B']);
							}
							$text = $this->yearValues[$num]['rows'][$k][$l];
						} else {
							// it's the day of previous or next month
							$this->cb->SetFillColor($this->dayColorInactive['R'], $this->dayColorInactive['G'], $this->dayColorInactive['B']);
							$text = '';
						}
						// draw day
						$this->cb->Cell($dayWidth, $dayHeight, $text, 1, 1, 'C', 1);
						$x += $dayWidth;
					}
					$y += $dayHeight;
					$x -= $dayWidth*7;
				}
			}
		}
	}


	// draws agenda mode
	public function drawAgenda($agendaHeader, $events, $rowHeight, $bgColor, $lineColor, $scaleColorOne, $scaleColorTwo, $fontSize, $today, $todayFontSize) {
		$this->bgColor = $this->convertColor($bgColor);
		$this->lineColor = $this->convertColor($lineColor);
		$this->scaleColorOne = $this->convertColor($scaleColorOne);
		$this->scaleColorTwo = $this->convertColor($scaleColorTwo);
		$this->cb->addPage();
		$this->drawToday($today, $todayFontSize);
		$this->cb->setFontSize($fontSize);

		// sets column 
		$timeColWidth = 50;
		$dscColWidth = $this->cb->getPageWidth() - $this->offsetLeft - $this->offsetRight - $timeColWidth;
		$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2', 'color' => $this->lineColor);
		$this->cb->SetLineStyle($this->lineStyle);
		$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);

		$text = $this->textWrap($agendaHeader[0], $timeColWidth, $fontSize);
		$this->cb->Cell($timeColWidth, $rowHeight, $text, 1, 0, 'C', 1);
		$text = $this->textWrap($agendaHeader[1], $dscColWidth, $fontSize);
		$this->cb->Cell($dscColWidth, $rowHeight, $text, 1, 1, 'C', 1);

		for ($i = 0; $i < count($events); $i++) {
			// checks if the page is over
			if ($this->cb->getY() + $rowHeight > $this->cb->getPageHeight() - $this->offsetBottom) {
				// add new page, draws page num, header and today label
				$this->cb->setPrintFooter(true);
				$this->cb->addPage();
				$this->drawToday($today, $todayFontSize);
				$this->cb->setFontSize($fontSize);
				$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);
				$text = $this->textWrap($agendaHeader[0], $timeColWidth, $fontSize);
				$this->cb->Cell($timeColWidth, $rowHeight, $text, 1, 0, 'C', 1);
				$text = $this->textWrap($agendaHeader[1], $dscColWidth, $fontSize);
				$this->cb->Cell($dscColWidth, $rowHeight, $text, 1, 1, 'C', 1);
			}
			// selects scale color
			if ($i%2 == 0) {
				$this->cb->SetFillColor($this->scaleColorOne['R'], $this->scaleColorOne['G'], $this->scaleColorOne['B']);
			} else {
				$this->cb->SetFillColor($this->scaleColorTwo['R'], $this->scaleColorTwo['G'], $this->scaleColorTwo['B']);
			}
			// draws time cell
			$text = $this->textWrap($events[$i]['head'], $timeColWidth, $fontSize);
			$this->cb->Cell($timeColWidth, $rowHeight, $text, 1, 0, 'L', 1);
			// draws description cell
			$text = $this->textWrap($events[$i]['body'], $dscColWidth, $fontSize);
			$this->cb->Cell($dscColWidth, $rowHeight, $text, 1, 1, 'L', 1);
		}
	}


	// draws headers and grid for day and week mode
	public function drawDayWeekHeader($columnHeader, $rowHeader, $topHeight, $leftWidth, $bgColor, $lineColor, $scaleColorOne, $scaleColorTwo, $dayHeaderFontSize, $dayScaleFontSize, $mode) {
		$this->cb->addPage();
		$this->bgColor = $this->convertColor($bgColor);
		$this->lineColor = $this->convertColor($lineColor);
		$this->scaleColorOne = $this->convertColor($scaleColorOne);
		$this->scaleColorTwo = $this->convertColor($scaleColorTwo);

		$this->leftWidth = $leftWidth;
		$this->topHeight = $topHeight;

		$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2', 'color' => $this->lineColor);
		$this->cb->SetLineStyle($this->lineStyle);
		$this->cb->SetFillColor($this->bgColor['R'], $this->bgColor['G'], $this->bgColor['B']);

		// calculates cell width and height
		$this->colWidth = ($this->cb->getPageWidth() - $this->offsetLeft - $this->offsetRight - $leftWidth)/count($columnHeader);
		$this->colHeight = ($this->cb->getPageHeight() - $this->offsetTop - $this->offsetBottom - $topHeight)/count($rowHeader);

		$this->cb->setX($this->offsetLeft + $leftWidth);
		$this->cb->setY($this->offsetTop, false);
		
		// draws scales on top
		$this->cb->setFontSize($dayHeaderFontSize);
		for ($i = 0; $i < count($columnHeader); $i++) {
			$Ln = ($i == count($columnHeader) - 1) ? 1 : 0;
			$this->cb->Cell($this->colWidth, $topHeight, $columnHeader[$i], 1, $Ln, 'C', 1);
		}

		$this->cb->setFontSize($dayScaleFontSize);
		// draws scales on left
		for ($i = 0; $i < count($rowHeader); $i++) {
			$this->cb->Cell($leftWidth, $this->colHeight, $rowHeader[$i], 1, 1, 'C', 1);
		}

		$this->cb->setX($this->offsetLeft + $leftWidth);
		$this->cb->setY($this->offsetTop + $topHeight, false);
		// circle for drawing scales

		for ($i = 0; $i < count($rowHeader); $i++) {
			// draws white line
			$this->cb->SetFillColor($this->scaleColorOne['R'], $this->scaleColorOne['G'], $this->scaleColorOne['B']);
			$border = (($i == 0)||($mode == 'bw')) ? 'LRT' : 'LR';
			$this->cb->Cell($this->colWidth*count($columnHeader), $this->colHeight/2, '', $border, 1, 'C', 1);
			// draws blue line
			$this->cb->setX($this->offsetLeft + $leftWidth);
			$this->cb->SetFillColor($this->scaleColorTwo['R'], $this->scaleColorTwo['G'], $this->scaleColorTwo['B']);
			$border = (($i == count($rowHeader) - 1)||($mode == 'bw')) ? 'LRB' : 'LR';
			$this->cb->Cell($this->colWidth*count($columnHeader), $this->colHeight/2, '', $border, 1, 'C', 1);
			$this->cb->setX($this->offsetLeft + $leftWidth);
		}
		// draws lines delemiters between days if it's week mode
		if (count($columnHeader > 1)) {
			for ($i = 0; $i < count($columnHeader) - 1; $i++) {
				$x = $this->offsetLeft + $leftWidth + ($i + 1)*$this->colWidth;
				$y1 = $this->offsetTop + $topHeight;
				$y2 = $this->cb->getPageHeight() - $this->offsetBottom;
				$this->cb->line($x, $y1, $x, $y2);
			}
		}
	}


	// draws events in day and week modes
	public function drawDayWeekEvents($events, $eventColor, $eventBorderColor, $dayEventHeaderFontSize, $dayEventBodyFontSize, $mode) {
		$this->eventColor = $this->convertColor($eventColor);
		$this->eventBorderColor = $this->convertColor($eventBorderColor);
		
		$eventHeaderHeight = 4;
		
		$this->lineStyle = Array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '0', 'color' => $this->eventBorderColor);
		$this->cb->setLineStyle($this->lineStyle);
		

		// circle for every event
		for ($i = 0; $i < count($events); $i++) {
			$event = $events[$i];
			// sets event color
			$color = $this->processEventColor($event['color']);
			if (($color == 'transparent')||($mode !== 'color')) {
				$this->cb->SetFillColor($this->eventColor['R'], $this->eventColor['G'], $this->eventColor['B']);
			} else {
				$color = $this->convertColor($color);
				$this->cb->SetFillColor($color['R'], $color['G'], $color['B']);
			}

			// calculates x-, y-coordinates, width and height of event
			$x = $this->offsetLeft + $this->leftWidth + $event['x']*$this->colWidth/100;
			$y = $this->offsetTop + $this->topHeight + $event['y']*$this->colHeight/100;
			$width = $event['width']*$this->colWidth/100 - $this->colWidth/60;
			$height = $event['height']*$this->colHeight/100;
			
			// draws event container
			$this->cb->RoundedRect($x, $y, $width, $height, 1, '1111', 'DF', array(), $this->eventColor);
			$this->cb->setX($x);
			$this->cb->setY($y, false);
			// draws event header
			$this->cb->setFontSize($dayEventHeaderFontSize);
			$text = $this->textWrap($event['head'], $width, $dayEventHeaderFontSize);
			$this->cb->Cell($width, $eventHeaderHeight, $text, 0, 0, 'C', 0);
			$y += $eventHeaderHeight;
			$height -= $eventHeaderHeight;
			// draws event body
			$this->cb->setFontSize($dayEventBodyFontSize);
			$this->cb->MultiCell($width, $height, $event['body'], 0, 'L', 0, 0, $x, $y,  true, 0, false, true, $height);
			// draws separate line
			$this->cb->line($x, $y, $x + $width, $y);
		}
	}


	// draws today value
	public function drawToday($today, $todayFontSize) {
		$this->cb->setFontSize($todayFontSize);
		$this->cb->Text($this->offsetLeft, $this->offsetTop - 2, $today);
	}


	// wraps text accordigly getted width and font size
	private function textWrap($text, $width, $fontSize) {
		$strWidth = $this->cb->GetStringWidth($text, '', '', $fontSize, false);
		// if text should be wrapped
		if ($strWidth >= $width) {
			$newStr = '';
			$newW = 0;
			$i = 0;
			// adds one symbol and checks text width
			while ($newW < $width - 1) {
				$newStr .= $text[$i];
				$newW = $this->cb->GetStringWidth($newStr.$text[$i + 1].'...', '', '', $fontSize, false);
				$i++;
			}
			return $newStr.'...';
		} else {
			return $text;
		}
	}


	// outputs PDF in browser
	public function pdfOut() {
		// send PDF-file in browser
		$this->cb->Output('grid.pdf', 'I');
	}


	// converts color from "ffffff" to Array('R' => 255, 'G' => 255, 'B' => 255)
	private function convertColor($colorHex) {
		$final = Array();
		$final['R'] = hexdec(substr($colorHex, 0, 2));
		$final['G'] = hexdec(substr($colorHex, 2, 2));
		$final['B'] = hexdec(substr($colorHex, 4, 2));
		return $final;
	}
	
	
	// convert event color ot RGB-format
	private function processEventColor($color) {
		if ($color == 'transparent') {
			return $color;
		}

		if (preg_match("/#[0-9A-Fa-f]{6}/", $color)) {
			return substr($color, 1);
		}
		$result = preg_match_all("/rgb\s?\((\d{1,3})\s?,\s?(\d{1,3})\s?,\s?(\d{1,3})\)/", $color, $rgb);
		
		if ($result) {
			$color = '';
			for ($i = 1; $i <= 3; $i++) {
				$comp = dechex($rgb[$i][0]);
				if (strlen($comp) == 1) {
					$comp = '0'.$comp;
					}
				$color .= $comp;
			}
			return $color;
		} else {
			return 'transparent';
		}
	}

}

?>