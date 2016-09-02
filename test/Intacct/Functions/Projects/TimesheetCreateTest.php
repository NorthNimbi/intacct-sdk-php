<?php

/**
 * Copyright 2016 Intacct Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"). You may not
 * use this file except in compliance with the License. You may obtain a copy
 * of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "LICENSE" file accompanying this file. This file is distributed on
 * an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Intacct\Functions\Projects;

use Intacct\FieldTypes\DateType;
use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class TimesheetCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\Projects\TimesheetCreate::writeXml
     */
    public function testDefaultParams()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create>
        <TIMESHEET>
            <EMPLOYEEID>E1234</EMPLOYEEID>
            <BEGINDATE>06/30/2016</BEGINDATE>
            <TIMESHEETENTRIES>
                <TIMESHEETENTRY>
                    <ENTRYDATE>06/30/2016</ENTRYDATE>
                    <QTY>1.75</QTY>
                </TIMESHEETENTRY>
            </TIMESHEETENTRIES>
        </TIMESHEET>
    </create>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $record = new TimesheetCreate('unittest');
        $record->setEmployeeId('E1234');
        $record->setBeginDate(new DateType('2016-06-30'));

        $entry1 = new TimesheetEntryCreate();
        $entry1->setEntryDate(new DateType('2016-06-30'));
        $entry1->setQuantity(1.75);

        $record->setEntries([
            $entry1,
        ]);

        $record->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\Projects\TimesheetCreate::writeXml
     */
    public function testParamOverrides()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create>
        <TIMESHEET>
            <EMPLOYEEID>E1234</EMPLOYEEID>
            <BEGINDATE>06/30/2016</BEGINDATE>
            <DESCRIPTION>desc</DESCRIPTION>
            <SUPDOCID>A1234</SUPDOCID>
            <STATE>Submitted</STATE>
            <TIMESHEETENTRIES>
                <TIMESHEETENTRY>
                    <ENTRYDATE>06/30/2016</ENTRYDATE>
                    <QTY>1.75</QTY>
                </TIMESHEETENTRY>
            </TIMESHEETENTRIES>
            <customfield1>customvalue1</customfield1>
        </TIMESHEET>
    </create>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $record = new TimesheetCreate('unittest');
        $record->setEmployeeId('E1234');
        $record->setBeginDate(new DateType('2016-06-30'));
        $record->setDescription('desc');
        $record->setAttachmentsId('A1234');
        $record->setAction('Submitted');
        $record->setCustomFields([
            'customfield1' => 'customvalue1',
        ]);

        $entry1 = new TimesheetEntryCreate();
        $entry1->setEntryDate(new DateType('2016-06-30'));
        $entry1->setQuantity(1.75);

        $record->setEntries([
            $entry1,
        ]);

        $record->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\Projects\TimesheetCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Employee ID is required for create
     */
    public function testRequiredEmployeeId()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $record = new TimesheetCreate('unittest');

        $record->writeXml($xml);
    }

    /**
     * @covers Intacct\Functions\Projects\TimesheetCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Begin Date is required for create
     */
    public function testRequiredBeginDate()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $record = new TimesheetCreate('unittest');
        $record->setEmployeeId('E1234');

        $record->writeXml($xml);
    }

    /**
     * @covers Intacct\Functions\Projects\TimesheetCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Timesheet must have at least 1 entry
     */
    public function testRequiredEntries()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $record = new TimesheetCreate('unittest');
        $record->setEmployeeId('E1234');
        $record->setBeginDate(new DateType('2016-06-30'));

        $record->writeXml($xml);
    }
}
