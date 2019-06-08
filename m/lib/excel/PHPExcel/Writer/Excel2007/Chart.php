<?php

class PHPExcel_Writer_Excel2007_Chart extends PHPExcel_Writer_Excel2007_WriterPart
{
	public function writeChart(PHPExcel_Chart $pChart = NULL)
	{
		$objWriter = NULL;

		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
		}
		else {
			$objWriter = new PHPExcel_Shared_XMLWriter(PHPExcel_Shared_XMLWriter::STORAGE_MEMORY);
		}

		$pChart->refresh();
		$objWriter->startDocument('1.0', 'UTF-8', 'yes');
		$objWriter->startElement('c:chartSpace');
		$objWriter->writeAttribute('xmlns:c', 'http://schemas.openxmlformats.org/drawingml/2006/chart');
		$objWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
		$objWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
		$objWriter->startElement('c:date1904');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$objWriter->startElement('c:lang');
		$objWriter->writeAttribute('val', 'en-GB');
		$objWriter->endElement();
		$objWriter->startElement('c:roundedCorners');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$this->_writeAlternateContent($objWriter);
		$objWriter->startElement('c:chart');
		$this->_writeTitle($pChart->getTitle(), $objWriter);
		$objWriter->startElement('c:autoTitleDeleted');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$this->_writePlotArea($pChart->getPlotArea(), $pChart->getXAxisLabel(), $pChart->getYAxisLabel(), $objWriter, $pChart->getWorksheet());
		$this->_writeLegend($pChart->getLegend(), $objWriter);
		$objWriter->startElement('c:plotVisOnly');
		$objWriter->writeAttribute('val', 1);
		$objWriter->endElement();
		$objWriter->startElement('c:dispBlanksAs');
		$objWriter->writeAttribute('val', 'gap');
		$objWriter->endElement();
		$objWriter->startElement('c:showDLblsOverMax');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$objWriter->endElement();
		$this->_writePrintSettings($objWriter);
		$objWriter->endElement();
		return $objWriter->getData();
	}

	private function _writeTitle(PHPExcel_Chart_Title $title = NULL, $objWriter)
	{
		if (is_null($title)) {
			return NULL;
		}

		$objWriter->startElement('c:title');
		$objWriter->startElement('c:tx');
		$objWriter->startElement('c:rich');
		$objWriter->startElement('a:bodyPr');
		$objWriter->endElement();
		$objWriter->startElement('a:lstStyle');
		$objWriter->endElement();
		$objWriter->startElement('a:p');
		$caption = $title->getCaption();
		if (is_array($caption) && (0 < count($caption))) {
			$caption = $caption[0];
		}

		$this->getParentWriter()->getWriterPart('stringtable')->writeRichTextForCharts($objWriter, $caption, 'a');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->endElement();
		$layout = $title->getLayout();
		$this->_writeLayout($layout, $objWriter);
		$objWriter->startElement('c:overlay');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$objWriter->endElement();
	}

	private function _writeLegend(PHPExcel_Chart_Legend $legend = NULL, $objWriter)
	{
		if (is_null($legend)) {
			return NULL;
		}

		$objWriter->startElement('c:legend');
		$objWriter->startElement('c:legendPos');
		$objWriter->writeAttribute('val', $legend->getPosition());
		$objWriter->endElement();
		$layout = $legend->getLayout();
		$this->_writeLayout($layout, $objWriter);
		$objWriter->startElement('c:overlay');
		$objWriter->writeAttribute('val', $legend->getOverlay() ? '1' : '0');
		$objWriter->endElement();
		$objWriter->startElement('c:txPr');
		$objWriter->startElement('a:bodyPr');
		$objWriter->endElement();
		$objWriter->startElement('a:lstStyle');
		$objWriter->endElement();
		$objWriter->startElement('a:p');
		$objWriter->startElement('a:pPr');
		$objWriter->writeAttribute('rtl', 0);
		$objWriter->startElement('a:defRPr');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->startElement('a:endParaRPr');
		$objWriter->writeAttribute('lang', 'en-US');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->endElement();
	}

	private function _writePlotArea(PHPExcel_Chart_PlotArea $plotArea, PHPExcel_Chart_Title $xAxisLabel = NULL, PHPExcel_Chart_Title $yAxisLabel = NULL, $objWriter, PHPExcel_Worksheet $pSheet)
	{
		if (is_null($plotArea)) {
			return NULL;
		}

		$id1 = $id2 = 0;
		$this->_seriesIndex = 0;
		$objWriter->startElement('c:plotArea');
		$layout = $plotArea->getLayout();
		$this->_writeLayout($layout, $objWriter);
		$chartTypes = self::_getChartType($plotArea);
		$catIsMultiLevelSeries = $valIsMultiLevelSeries = false;
		$plotGroupingType = '';

		foreach ($chartTypes as $chartType) {
			$objWriter->startElement('c:' . $chartType);
			$groupCount = $plotArea->getPlotGroupCount();

			for ($i = 0; $i < $groupCount; ++$i) {
				$plotGroup = $plotArea->getPlotGroupByIndex($i);
				$groupType = $plotGroup->getPlotType();

				if ($groupType == $chartType) {
					$plotStyle = $plotGroup->getPlotStyle();

					if ($groupType === PHPExcel_Chart_DataSeries::TYPE_RADARCHART) {
						$objWriter->startElement('c:radarStyle');
						$objWriter->writeAttribute('val', $plotStyle);
						$objWriter->endElement();
					}
					else if ($groupType === PHPExcel_Chart_DataSeries::TYPE_SCATTERCHART) {
						$objWriter->startElement('c:scatterStyle');
						$objWriter->writeAttribute('val', $plotStyle);
						$objWriter->endElement();
					}

					$this->_writePlotGroup($plotGroup, $chartType, $objWriter, $catIsMultiLevelSeries, $valIsMultiLevelSeries, $plotGroupingType, $pSheet);
				}
			}

			$this->_writeDataLbls($objWriter, $layout);

			if ($chartType === PHPExcel_Chart_DataSeries::TYPE_LINECHART) {
				$objWriter->startElement('c:smooth');
				$objWriter->writeAttribute('val', (int) $plotGroup->getSmoothLine());
				$objWriter->endElement();
			}
			else {
				if (($chartType === PHPExcel_Chart_DataSeries::TYPE_BARCHART) || ($chartType === PHPExcel_Chart_DataSeries::TYPE_BARCHART_3D)) {
					$objWriter->startElement('c:gapWidth');
					$objWriter->writeAttribute('val', 150);
					$objWriter->endElement();
					if (($plotGroupingType == 'percentStacked') || ($plotGroupingType == 'stacked')) {
						$objWriter->startElement('c:overlap');
						$objWriter->writeAttribute('val', 100);
						$objWriter->endElement();
					}
				}
				else if ($chartType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) {
					$objWriter->startElement('c:bubbleScale');
					$objWriter->writeAttribute('val', 25);
					$objWriter->endElement();
					$objWriter->startElement('c:showNegBubbles');
					$objWriter->writeAttribute('val', 0);
					$objWriter->endElement();
				}
				else if ($chartType === PHPExcel_Chart_DataSeries::TYPE_STOCKCHART) {
					$objWriter->startElement('c:hiLowLines');
					$objWriter->endElement();
					$objWriter->startElement('c:upDownBars');
					$objWriter->startElement('c:gapWidth');
					$objWriter->writeAttribute('val', 300);
					$objWriter->endElement();
					$objWriter->startElement('c:upBars');
					$objWriter->endElement();
					$objWriter->startElement('c:downBars');
					$objWriter->endElement();
					$objWriter->endElement();
				}
			}

			$id1 = '75091328';
			$id2 = '75089408';
			if (($chartType !== PHPExcel_Chart_DataSeries::TYPE_PIECHART) && ($chartType !== PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) && ($chartType !== PHPExcel_Chart_DataSeries::TYPE_DONUTCHART)) {
				$objWriter->startElement('c:axId');
				$objWriter->writeAttribute('val', $id1);
				$objWriter->endElement();
				$objWriter->startElement('c:axId');
				$objWriter->writeAttribute('val', $id2);
				$objWriter->endElement();
			}
			else {
				$objWriter->startElement('c:firstSliceAng');
				$objWriter->writeAttribute('val', 0);
				$objWriter->endElement();

				if ($chartType === PHPExcel_Chart_DataSeries::TYPE_DONUTCHART) {
					$objWriter->startElement('c:holeSize');
					$objWriter->writeAttribute('val', 50);
					$objWriter->endElement();
				}
			}

			$objWriter->endElement();
		}

		if (($chartType !== PHPExcel_Chart_DataSeries::TYPE_PIECHART) && ($chartType !== PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) && ($chartType !== PHPExcel_Chart_DataSeries::TYPE_DONUTCHART)) {
			if ($chartType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) {
				$this->_writeValAx($objWriter, $plotArea, $xAxisLabel, $chartType, $id1, $id2, $catIsMultiLevelSeries);
			}
			else {
				$this->_writeCatAx($objWriter, $plotArea, $xAxisLabel, $chartType, $id1, $id2, $catIsMultiLevelSeries);
			}

			$this->_writeValAx($objWriter, $plotArea, $yAxisLabel, $chartType, $id1, $id2, $valIsMultiLevelSeries);
		}

		$objWriter->endElement();
	}

	private function _writeDataLbls($objWriter, $chartLayout)
	{
		$objWriter->startElement('c:dLbls');
		$objWriter->startElement('c:showLegendKey');
		$showLegendKey = (empty($chartLayout) ? 0 : $chartLayout->getShowLegendKey());
		$objWriter->writeAttribute('val', empty($showLegendKey) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showVal');
		$showVal = (empty($chartLayout) ? 0 : $chartLayout->getShowVal());
		$objWriter->writeAttribute('val', empty($showVal) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showCatName');
		$showCatName = (empty($chartLayout) ? 0 : $chartLayout->getShowCatName());
		$objWriter->writeAttribute('val', empty($showCatName) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showSerName');
		$showSerName = (empty($chartLayout) ? 0 : $chartLayout->getShowSerName());
		$objWriter->writeAttribute('val', empty($showSerName) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showPercent');
		$showPercent = (empty($chartLayout) ? 0 : $chartLayout->getShowPercent());
		$objWriter->writeAttribute('val', empty($showPercent) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showBubbleSize');
		$showBubbleSize = (empty($chartLayout) ? 0 : $chartLayout->getShowBubbleSize());
		$objWriter->writeAttribute('val', empty($showBubbleSize) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->startElement('c:showLeaderLines');
		$showLeaderLines = (empty($chartLayout) ? 1 : $chartLayout->getShowLeaderLines());
		$objWriter->writeAttribute('val', empty($showLeaderLines) ? 0 : 1);
		$objWriter->endElement();
		$objWriter->endElement();
	}

	private function _writeCatAx($objWriter, PHPExcel_Chart_PlotArea $plotArea, $xAxisLabel, $groupType, $id1, $id2, $isMultiLevelSeries)
	{
		$objWriter->startElement('c:catAx');

		if (0 < $id1) {
			$objWriter->startElement('c:axId');
			$objWriter->writeAttribute('val', $id1);
			$objWriter->endElement();
		}

		$objWriter->startElement('c:scaling');
		$objWriter->startElement('c:orientation');
		$objWriter->writeAttribute('val', 'minMax');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->startElement('c:delete');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$objWriter->startElement('c:axPos');
		$objWriter->writeAttribute('val', 'b');
		$objWriter->endElement();

		if (!is_null($xAxisLabel)) {
			$objWriter->startElement('c:title');
			$objWriter->startElement('c:tx');
			$objWriter->startElement('c:rich');
			$objWriter->startElement('a:bodyPr');
			$objWriter->endElement();
			$objWriter->startElement('a:lstStyle');
			$objWriter->endElement();
			$objWriter->startElement('a:p');
			$objWriter->startElement('a:r');
			$caption = $xAxisLabel->getCaption();

			if (is_array($caption)) {
				$caption = $caption[0];
			}

			$objWriter->startElement('a:t');
			$objWriter->writeRawData(PHPExcel_Shared_String::ControlCharacterPHP2OOXML($caption));
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();
			$layout = $xAxisLabel->getLayout();
			$this->_writeLayout($layout, $objWriter);
			$objWriter->startElement('c:overlay');
			$objWriter->writeAttribute('val', 0);
			$objWriter->endElement();
			$objWriter->endElement();
		}

		$objWriter->startElement('c:numFmt');
		$objWriter->writeAttribute('formatCode', 'General');
		$objWriter->writeAttribute('sourceLinked', 1);
		$objWriter->endElement();
		$objWriter->startElement('c:majorTickMark');
		$objWriter->writeAttribute('val', 'out');
		$objWriter->endElement();
		$objWriter->startElement('c:minorTickMark');
		$objWriter->writeAttribute('val', 'none');
		$objWriter->endElement();
		$objWriter->startElement('c:tickLblPos');
		$objWriter->writeAttribute('val', 'nextTo');
		$objWriter->endElement();

		if (0 < $id2) {
			$objWriter->startElement('c:crossAx');
			$objWriter->writeAttribute('val', $id2);
			$objWriter->endElement();
			$objWriter->startElement('c:crosses');
			$objWriter->writeAttribute('val', 'autoZero');
			$objWriter->endElement();
		}

		$objWriter->startElement('c:auto');
		$objWriter->writeAttribute('val', 1);
		$objWriter->endElement();
		$objWriter->startElement('c:lblAlgn');
		$objWriter->writeAttribute('val', 'ctr');
		$objWriter->endElement();
		$objWriter->startElement('c:lblOffset');
		$objWriter->writeAttribute('val', 100);
		$objWriter->endElement();

		if ($isMultiLevelSeries) {
			$objWriter->startElement('c:noMultiLvlLbl');
			$objWriter->writeAttribute('val', 0);
			$objWriter->endElement();
		}

		$objWriter->endElement();
	}

	private function _writeValAx($objWriter, PHPExcel_Chart_PlotArea $plotArea, $yAxisLabel, $groupType, $id1, $id2, $isMultiLevelSeries)
	{
		$objWriter->startElement('c:valAx');

		if (0 < $id2) {
			$objWriter->startElement('c:axId');
			$objWriter->writeAttribute('val', $id2);
			$objWriter->endElement();
		}

		$objWriter->startElement('c:scaling');
		$objWriter->startElement('c:orientation');
		$objWriter->writeAttribute('val', 'minMax');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->startElement('c:delete');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
		$objWriter->startElement('c:axPos');
		$objWriter->writeAttribute('val', 'l');
		$objWriter->endElement();
		$objWriter->startElement('c:majorGridlines');
		$objWriter->endElement();

		if (!is_null($yAxisLabel)) {
			$objWriter->startElement('c:title');
			$objWriter->startElement('c:tx');
			$objWriter->startElement('c:rich');
			$objWriter->startElement('a:bodyPr');
			$objWriter->endElement();
			$objWriter->startElement('a:lstStyle');
			$objWriter->endElement();
			$objWriter->startElement('a:p');
			$objWriter->startElement('a:r');
			$caption = $yAxisLabel->getCaption();

			if (is_array($caption)) {
				$caption = $caption[0];
			}

			$objWriter->startElement('a:t');
			$objWriter->writeRawData(PHPExcel_Shared_String::ControlCharacterPHP2OOXML($caption));
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();
			$objWriter->endElement();

			if ($groupType !== PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) {
				$layout = $yAxisLabel->getLayout();
				$this->_writeLayout($layout, $objWriter);
			}

			$objWriter->startElement('c:overlay');
			$objWriter->writeAttribute('val', 0);
			$objWriter->endElement();
			$objWriter->endElement();
		}

		$objWriter->startElement('c:numFmt');
		$objWriter->writeAttribute('formatCode', 'General');
		$objWriter->writeAttribute('sourceLinked', 1);
		$objWriter->endElement();
		$objWriter->startElement('c:majorTickMark');
		$objWriter->writeAttribute('val', 'out');
		$objWriter->endElement();
		$objWriter->startElement('c:minorTickMark');
		$objWriter->writeAttribute('val', 'none');
		$objWriter->endElement();
		$objWriter->startElement('c:tickLblPos');
		$objWriter->writeAttribute('val', 'nextTo');
		$objWriter->endElement();

		if (0 < $id1) {
			$objWriter->startElement('c:crossAx');
			$objWriter->writeAttribute('val', $id2);
			$objWriter->endElement();
			$objWriter->startElement('c:crosses');
			$objWriter->writeAttribute('val', 'autoZero');
			$objWriter->endElement();
			$objWriter->startElement('c:crossBetween');
			$objWriter->writeAttribute('val', 'midCat');
			$objWriter->endElement();
		}

		if ($isMultiLevelSeries) {
			if ($groupType !== PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) {
				$objWriter->startElement('c:noMultiLvlLbl');
				$objWriter->writeAttribute('val', 0);
				$objWriter->endElement();
			}
		}

		$objWriter->endElement();
	}

	static private function _getChartType($plotArea)
	{
		$groupCount = $plotArea->getPlotGroupCount();

		if ($groupCount == 1) {
			$chartType = array($plotArea->getPlotGroupByIndex(0)->getPlotType());
		}
		else {
			$chartTypes = array();

			for ($i = 0; $i < $groupCount; ++$i) {
				$chartTypes[] = $plotArea->getPlotGroupByIndex($i)->getPlotType();
			}

			$chartType = array_unique($chartTypes);

			if (count($chartTypes) == 0) {
				throw new PHPExcel_Writer_Exception('Chart is not yet implemented');
			}
		}

		return $chartType;
	}

	private function _writePlotGroup($plotGroup, $groupType, $objWriter, &$catIsMultiLevelSeries, &$valIsMultiLevelSeries, &$plotGroupingType, PHPExcel_Worksheet $pSheet)
	{
		if (is_null($plotGroup)) {
			return NULL;
		}

		if (($groupType == PHPExcel_Chart_DataSeries::TYPE_BARCHART) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_BARCHART_3D)) {
			$objWriter->startElement('c:barDir');
			$objWriter->writeAttribute('val', $plotGroup->getPlotDirection());
			$objWriter->endElement();
		}

		if (!is_null($plotGroup->getPlotGrouping())) {
			$plotGroupingType = $plotGroup->getPlotGrouping();
			$objWriter->startElement('c:grouping');
			$objWriter->writeAttribute('val', $plotGroupingType);
			$objWriter->endElement();
		}

		$plotSeriesOrder = $plotGroup->getPlotOrder();
		$plotSeriesCount = count($plotSeriesOrder);
		if (($groupType !== PHPExcel_Chart_DataSeries::TYPE_RADARCHART) && ($groupType !== PHPExcel_Chart_DataSeries::TYPE_STOCKCHART)) {
			if ($groupType !== PHPExcel_Chart_DataSeries::TYPE_LINECHART) {
				if (($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_DONUTCHART) || (1 < $plotSeriesCount)) {
					$objWriter->startElement('c:varyColors');
					$objWriter->writeAttribute('val', 1);
					$objWriter->endElement();
				}
				else {
					$objWriter->startElement('c:varyColors');
					$objWriter->writeAttribute('val', 0);
					$objWriter->endElement();
				}
			}
		}

		foreach ($plotSeriesOrder as $plotSeriesIdx => $plotSeriesRef) {
			$objWriter->startElement('c:ser');
			$objWriter->startElement('c:idx');
			$objWriter->writeAttribute('val', $this->_seriesIndex + $plotSeriesIdx);
			$objWriter->endElement();
			$objWriter->startElement('c:order');
			$objWriter->writeAttribute('val', $this->_seriesIndex + $plotSeriesRef);
			$objWriter->endElement();
			if (($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_DONUTCHART)) {
				$objWriter->startElement('c:dPt');
				$objWriter->startElement('c:idx');
				$objWriter->writeAttribute('val', 3);
				$objWriter->endElement();
				$objWriter->startElement('c:bubble3D');
				$objWriter->writeAttribute('val', 0);
				$objWriter->endElement();
				$objWriter->startElement('c:spPr');
				$objWriter->startElement('a:solidFill');
				$objWriter->startElement('a:srgbClr');
				$objWriter->writeAttribute('val', 'FF9900');
				$objWriter->endElement();
				$objWriter->endElement();
				$objWriter->endElement();
				$objWriter->endElement();
			}

			$plotSeriesLabel = $plotGroup->getPlotLabelByIndex($plotSeriesRef);
			if ($plotSeriesLabel && (0 < $plotSeriesLabel->getPointCount())) {
				$objWriter->startElement('c:tx');
				$objWriter->startElement('c:strRef');
				$this->_writePlotSeriesLabel($plotSeriesLabel, $objWriter);
				$objWriter->endElement();
				$objWriter->endElement();
			}

			if (($groupType == PHPExcel_Chart_DataSeries::TYPE_LINECHART) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_STOCKCHART)) {
				$objWriter->startElement('c:spPr');
				$objWriter->startElement('a:ln');
				$objWriter->writeAttribute('w', 12700);

				if ($groupType == PHPExcel_Chart_DataSeries::TYPE_STOCKCHART) {
					$objWriter->startElement('a:noFill');
					$objWriter->endElement();
				}

				$objWriter->endElement();
				$objWriter->endElement();
			}

			$plotSeriesValues = $plotGroup->getPlotValuesByIndex($plotSeriesRef);

			if ($plotSeriesValues) {
				$plotSeriesMarker = $plotSeriesValues->getPointMarker();

				if ($plotSeriesMarker) {
					$objWriter->startElement('c:marker');
					$objWriter->startElement('c:symbol');
					$objWriter->writeAttribute('val', $plotSeriesMarker);
					$objWriter->endElement();

					if ($plotSeriesMarker !== 'none') {
						$objWriter->startElement('c:size');
						$objWriter->writeAttribute('val', 3);
						$objWriter->endElement();
					}

					$objWriter->endElement();
				}
			}

			if (($groupType === PHPExcel_Chart_DataSeries::TYPE_BARCHART) || ($groupType === PHPExcel_Chart_DataSeries::TYPE_BARCHART_3D) || ($groupType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART)) {
				$objWriter->startElement('c:invertIfNegative');
				$objWriter->writeAttribute('val', 0);
				$objWriter->endElement();
			}

			$plotSeriesCategory = $plotGroup->getPlotCategoryByIndex($plotSeriesRef);
			if ($plotSeriesCategory && (0 < $plotSeriesCategory->getPointCount())) {
				$catIsMultiLevelSeries = $catIsMultiLevelSeries || $plotSeriesCategory->isMultiLevelSeries();
				if (($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) || ($groupType == PHPExcel_Chart_DataSeries::TYPE_DONUTCHART)) {
					if (!is_null($plotGroup->getPlotStyle())) {
						$plotStyle = $plotGroup->getPlotStyle();

						if ($plotStyle) {
							$objWriter->startElement('c:explosion');
							$objWriter->writeAttribute('val', 25);
							$objWriter->endElement();
						}
					}
				}

				if (($groupType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) || ($groupType === PHPExcel_Chart_DataSeries::TYPE_SCATTERCHART)) {
					$objWriter->startElement('c:xVal');
				}
				else {
					$objWriter->startElement('c:cat');
				}

				$this->_writePlotSeriesValues($plotSeriesCategory, $objWriter, $groupType, 'str', $pSheet);
				$objWriter->endElement();
			}

			if ($plotSeriesValues) {
				$valIsMultiLevelSeries = $valIsMultiLevelSeries || $plotSeriesValues->isMultiLevelSeries();
				if (($groupType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) || ($groupType === PHPExcel_Chart_DataSeries::TYPE_SCATTERCHART)) {
					$objWriter->startElement('c:yVal');
				}
				else {
					$objWriter->startElement('c:val');
				}

				$this->_writePlotSeriesValues($plotSeriesValues, $objWriter, $groupType, 'num', $pSheet);
				$objWriter->endElement();
			}

			if ($groupType === PHPExcel_Chart_DataSeries::TYPE_BUBBLECHART) {
				$this->_writeBubbles($plotSeriesValues, $objWriter, $pSheet);
			}

			$objWriter->endElement();
		}

		$this->_seriesIndex += $plotSeriesIdx + 1;
	}

	private function _writePlotSeriesLabel($plotSeriesLabel, $objWriter)
	{
		if (is_null($plotSeriesLabel)) {
			return NULL;
		}

		$objWriter->startElement('c:f');
		$objWriter->writeRawData($plotSeriesLabel->getDataSource());
		$objWriter->endElement();
		$objWriter->startElement('c:strCache');
		$objWriter->startElement('c:ptCount');
		$objWriter->writeAttribute('val', $plotSeriesLabel->getPointCount());
		$objWriter->endElement();

		foreach ($plotSeriesLabel->getDataValues() as $plotLabelKey => $plotLabelValue) {
			$objWriter->startElement('c:pt');
			$objWriter->writeAttribute('idx', $plotLabelKey);
			$objWriter->startElement('c:v');
			$objWriter->writeRawData($plotLabelValue);
			$objWriter->endElement();
			$objWriter->endElement();
		}

		$objWriter->endElement();
	}

	private function _writePlotSeriesValues($plotSeriesValues, $objWriter, $groupType, $dataType = 'str', PHPExcel_Worksheet $pSheet)
	{
		if (is_null($plotSeriesValues)) {
			return NULL;
		}

		if ($plotSeriesValues->isMultiLevelSeries()) {
			$levelCount = $plotSeriesValues->multiLevelCount();
			$objWriter->startElement('c:multiLvlStrRef');
			$objWriter->startElement('c:f');
			$objWriter->writeRawData($plotSeriesValues->getDataSource());
			$objWriter->endElement();
			$objWriter->startElement('c:multiLvlStrCache');
			$objWriter->startElement('c:ptCount');
			$objWriter->writeAttribute('val', $plotSeriesValues->getPointCount());
			$objWriter->endElement();

			for ($level = 0; $level < $levelCount; ++$level) {
				$objWriter->startElement('c:lvl');

				foreach ($plotSeriesValues->getDataValues() as $plotSeriesKey => $plotSeriesValue) {
					if (isset($plotSeriesValue[$level])) {
						$objWriter->startElement('c:pt');
						$objWriter->writeAttribute('idx', $plotSeriesKey);
						$objWriter->startElement('c:v');
						$objWriter->writeRawData($plotSeriesValue[$level]);
						$objWriter->endElement();
						$objWriter->endElement();
					}
				}

				$objWriter->endElement();
			}

			$objWriter->endElement();
			$objWriter->endElement();
		}
		else {
			$objWriter->startElement('c:' . $dataType . 'Ref');
			$objWriter->startElement('c:f');
			$objWriter->writeRawData($plotSeriesValues->getDataSource());
			$objWriter->endElement();
			$objWriter->startElement('c:' . $dataType . 'Cache');
			if (($groupType != PHPExcel_Chart_DataSeries::TYPE_PIECHART) && ($groupType != PHPExcel_Chart_DataSeries::TYPE_PIECHART_3D) && ($groupType != PHPExcel_Chart_DataSeries::TYPE_DONUTCHART)) {
				if (($plotSeriesValues->getFormatCode() !== NULL) && ($plotSeriesValues->getFormatCode() !== '')) {
					$objWriter->startElement('c:formatCode');
					$objWriter->writeRawData($plotSeriesValues->getFormatCode());
					$objWriter->endElement();
				}
			}

			$objWriter->startElement('c:ptCount');
			$objWriter->writeAttribute('val', $plotSeriesValues->getPointCount());
			$objWriter->endElement();
			$dataValues = $plotSeriesValues->getDataValues();

			if (!empty($dataValues)) {
				if (is_array($dataValues)) {
					foreach ($dataValues as $plotSeriesKey => $plotSeriesValue) {
						$objWriter->startElement('c:pt');
						$objWriter->writeAttribute('idx', $plotSeriesKey);
						$objWriter->startElement('c:v');
						$objWriter->writeRawData($plotSeriesValue);
						$objWriter->endElement();
						$objWriter->endElement();
					}
				}
			}

			$objWriter->endElement();
			$objWriter->endElement();
		}
	}

	private function _writeBubbles($plotSeriesValues, $objWriter, PHPExcel_Worksheet $pSheet)
	{
		if (is_null($plotSeriesValues)) {
			return NULL;
		}

		$objWriter->startElement('c:bubbleSize');
		$objWriter->startElement('c:numLit');
		$objWriter->startElement('c:formatCode');
		$objWriter->writeRawData('General');
		$objWriter->endElement();
		$objWriter->startElement('c:ptCount');
		$objWriter->writeAttribute('val', $plotSeriesValues->getPointCount());
		$objWriter->endElement();
		$dataValues = $plotSeriesValues->getDataValues();

		if (!empty($dataValues)) {
			if (is_array($dataValues)) {
				foreach ($dataValues as $plotSeriesKey => $plotSeriesValue) {
					$objWriter->startElement('c:pt');
					$objWriter->writeAttribute('idx', $plotSeriesKey);
					$objWriter->startElement('c:v');
					$objWriter->writeRawData(1);
					$objWriter->endElement();
					$objWriter->endElement();
				}
			}
		}

		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->startElement('c:bubble3D');
		$objWriter->writeAttribute('val', 0);
		$objWriter->endElement();
	}

	private function _writeLayout(PHPExcel_Chart_Layout $layout = NULL, $objWriter)
	{
		$objWriter->startElement('c:layout');

		if (!is_null($layout)) {
			$objWriter->startElement('c:manualLayout');
			$layoutTarget = $layout->getLayoutTarget();

			if (!is_null($layoutTarget)) {
				$objWriter->startElement('c:layoutTarget');
				$objWriter->writeAttribute('val', $layoutTarget);
				$objWriter->endElement();
			}

			$xMode = $layout->getXMode();

			if (!is_null($xMode)) {
				$objWriter->startElement('c:xMode');
				$objWriter->writeAttribute('val', $xMode);
				$objWriter->endElement();
			}

			$yMode = $layout->getYMode();

			if (!is_null($yMode)) {
				$objWriter->startElement('c:yMode');
				$objWriter->writeAttribute('val', $yMode);
				$objWriter->endElement();
			}

			$x = $layout->getXPosition();

			if (!is_null($x)) {
				$objWriter->startElement('c:x');
				$objWriter->writeAttribute('val', $x);
				$objWriter->endElement();
			}

			$y = $layout->getYPosition();

			if (!is_null($y)) {
				$objWriter->startElement('c:y');
				$objWriter->writeAttribute('val', $y);
				$objWriter->endElement();
			}

			$w = $layout->getWidth();

			if (!is_null($w)) {
				$objWriter->startElement('c:w');
				$objWriter->writeAttribute('val', $w);
				$objWriter->endElement();
			}

			$h = $layout->getHeight();

			if (!is_null($h)) {
				$objWriter->startElement('c:h');
				$objWriter->writeAttribute('val', $h);
				$objWriter->endElement();
			}

			$objWriter->endElement();
		}

		$objWriter->endElement();
	}

	private function _writeAlternateContent($objWriter)
	{
		$objWriter->startElement('mc:AlternateContent');
		$objWriter->writeAttribute('xmlns:mc', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
		$objWriter->startElement('mc:Choice');
		$objWriter->writeAttribute('xmlns:c14', 'http://schemas.microsoft.com/office/drawing/2007/8/2/chart');
		$objWriter->writeAttribute('Requires', 'c14');
		$objWriter->startElement('c14:style');
		$objWriter->writeAttribute('val', '102');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->startElement('mc:Fallback');
		$objWriter->startElement('c:style');
		$objWriter->writeAttribute('val', '2');
		$objWriter->endElement();
		$objWriter->endElement();
		$objWriter->endElement();
	}

	private function _writePrintSettings($objWriter)
	{
		$objWriter->startElement('c:printSettings');
		$objWriter->startElement('c:headerFooter');
		$objWriter->endElement();
		$objWriter->startElement('c:pageMargins');
		$objWriter->writeAttribute('footer', 0.3);
		$objWriter->writeAttribute('header', 0.3);
		$objWriter->writeAttribute('r', 0.7);
		$objWriter->writeAttribute('l', 0.7);
		$objWriter->writeAttribute('t', 0.75);
		$objWriter->writeAttribute('b', 0.75);
		$objWriter->endElement();
		$objWriter->startElement('c:pageSetup');
		$objWriter->writeAttribute('orientation', 'portrait');
		$objWriter->endElement();
		$objWriter->endElement();
	}
}

?>
