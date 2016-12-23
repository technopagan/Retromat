<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testShowSingleActivityBlock()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?id=32');

        $jsPlan = $crawler->filter('.js_plan');
        $activityBlocks = $jsPlan->filter('.js_activity_block');
        $this->assertEquals(1, $activityBlocks->count());
    }

    public function testShowActivityNameRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=32');
        $this->assertEquals(
            'Emoticon Project Gauge',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_name')->html()
        );

        $crawler = $client->request('GET', '/?id=59');
        $this->assertEquals(
            'Happiness Histogram',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_name')->html()
        );

        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=80');
        $this->assertEquals(
            'Repeat &amp; Avoid', // raw HTML imported to DB from lang/activities_en.php
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_name')->html()
        );
    }

    public function testShowActivitySummaries()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=76');
        $this->assertEquals(
            'Participants express what they admire about one another',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_summary')->text()
        );

        $crawler = $client->request('GET', '/?id=81');
        $this->assertEquals(
            'Everyone states what they want out of the retrospective',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_summary')->text()
        );
    }

    public function testShowActivityDescriptionsRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=22');
        $this->assertEquals(
            'Prepare a flipchart with a drawing of a thermometer from freezing to body temperature to hot. Each participant marks their mood on the sheet.',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_description')->html()
        );

        $crawler = $client->request('GET', '/?id=81');

        // Copied from FireBug as generated by JS version of retromat
        // $expected = 'Everyone in the team states their goal for the retrospective, i.e. what they want out of the meeting. Examples of what participants might say: <ul>     <li>I\'m happy if we get 1 good action item</li>     <li>I want to talk about our argument about unit tests and agree on how we\'ll do it in the future</li>     <li>I\'ll consider this retro a success, if we come up with a plan to tidy up $obscureModule</li> </ul> [You can check if these goals were met if you close with activity #14.] <br><br> [The <a href="http://liveingreatness.com/additional-protocols/meet/">Meet - Core Protocol</a>, which inspired this activity, also describes \'Alignment Checks\': Whenever someone thinks the retrospective is not meeting people\'s needs they can ask for an Alignment Check. Then everyone says a number from 0 to 10 which reflects how much they are getting what they want. The person with the lowest number takes over to get nearer to what they want.]';

        // This is identical except for some whitespace.
        // Normalizing this turned out to be anoying, so for now:
        // As long as this comes out, I'll be happy:
        $expected = 'Everyone in the team states their goal for the retrospective, i.e. what they want out of the meeting. Examples of what participants might say: <ul>
<li>I\'m happy if we get 1 good action item</li>     <li>I want to talk about our argument about unit tests and agree on how we\'ll do it in the future</li>     <li>I\'ll consider this retro a success, if we come up with a plan to tidy up $obscureModule</li> </ul> [You can check if these goals were met if you close with activity #14.] <br><br> [The <a href="http://liveingreatness.com/additional-protocols/meet/">Meet - Core Protocol</a>, which inspired this activity, also describes \'Alignment Checks\': Whenever someone thinks the retrospective is not meeting people\'s needs they can ask for an Alignment Check. Then everyone says a number from 0 to 10 which reflects how much they are getting what they want. The person with the lowest number takes over to get nearer to what they want.]';

        $this->assertEquals(
            $expected,
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_description')->html()
        );
    }

    public function testShowActivityLinksText()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=1');
        $this->assertEquals(
            '1',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_id')->text()
        );

        $crawler = $client->request('GET', '/?id=2');
        $this->assertEquals(
            '2',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_id')->text()
        );
    }

    public function testShowActivityLinksHref()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=1');
        $this->assertEquals(
            '?id=1',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_activity_link')->attr('href')
        );

        $crawler = $client->request('GET', '/?id=2');
        $this->assertEquals(
            '?id=2',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_activity_link')->attr('href')
        );
    }

    public function testShowActivityPhaseLinkText()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=3');
        $this->assertEquals(
            'Set the stage',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_phase_title')->text()
        );

        $crawler = $client->request('GET', '/?id=4');
        $this->assertEquals(
            'Gather data',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_phase_title')->text()
        );
    }


    public function testShowActivityPhaseLinkHref()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=1');
        $this->assertEquals(
            '?id=1-2-3-18-22-31-32-36-42-43-46-52-59-70-76-81-82-84-85-90-106-107-108-114-122&phase=0',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_phase_link')->attr('href')
        );

        $crawler = $client->request('GET', '/?id=5');
        $this->assertEquals(
            '?id=4-5-6-7-19-33-35-47-51-54-62-64-65-75-78-79-80-86-87-89-93-97-98-110-116-119-121-123-126&phase=1',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_phase_link')->attr('href')
        );
    }

    public function testShowActivitySourceSimpleStringRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=17');
        $this->assertEquals(
            '<a href="http://fairlygoodpractices.com/samolo.htm">Fairly good practices</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );

        $crawler = $client->request('GET', '/?id=80');
        $this->assertEquals(
            '<a href="http://www.infoq.com/minibooks/agile-retrospectives-value">Luis Goncalves</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );
    }

    public function testShowActivitySourcePlaceholderRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=77');
        $this->assertEquals(
            '<a href="https://leanpub.com/ErfolgreicheRetrospektiven">Judith Andresen</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );

        $crawler = $client->request('GET', '/?id=5');
        $this->assertEquals(
            '<a href="http://www.finding-marbles.com/">Corinna Baldauf</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );
    }

    public function testShowActivitySourcePlaceholderAndStringRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=15');
        $this->assertEquals(
            '<a href="http://www.amazon.com/Agile-Retrospectives-Making-Teams-Great/dp/0977616649/">Agile Retrospectives</a> who took it from \'The Satir Model: Family Therapy and Beyond\'',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );

        $crawler = $client->request('GET', '/?id=37');
        $this->assertEquals(
            '<a href="http://www.amazon.com/Innovation-Games-Creating-Breakthrough-Collaborative/dp/0321437292/">Luke Hohmann</a>, found at <a href="http://www.ayeconference.com/appreciativeretrospective/">Diana Larsen</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );
    }

    public function testShowActivitySourceStringAndPlaceholderRawHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=14');
        $this->assertEquals(
            'ALE 2011, <a href="http://www.finding-marbles.com/">Corinna Baldauf</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );

        $crawler = $client->request('GET', '/?id=65');
        $this->assertEquals(
            '<a href="http://blog.8thlight.com/doug-bradbury/2011/09/19/apreciative_inquiry_retrospectives.html">Doug Bradbury</a>, adapted for SW development by <a href="http://www.finding-marbles.com/">Corinna Baldauf</a>',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_fill_source')->html()
        );
    }

    public function testShowAny5Activities()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?id=3-87-113-13-16');
        $activities = $crawler->filter('.js_plan')->filter('.js_activity_block');
        $this->assertEquals(5, $activities->count());
        $this->assertEquals('3', $activities->eq(0)->filter('.js_fill_id')->text());
        $this->assertEquals('87', $activities->eq(1)->filter('.js_fill_id')->text());
        $this->assertEquals('113', $activities->eq(2)->filter('.js_fill_id')->text());
        $this->assertEquals('13', $activities->eq(3)->filter('.js_fill_id')->text());
        $this->assertEquals('16', $activities->eq(4)->filter('.js_fill_id')->text());

        $crawler = $client->request('GET', '/?id=1-91-2-55-100');
        $activities = $crawler->filter('.js_plan')->filter('.js_activity_block');
        $this->assertEquals(5, $activities->count());
        $this->assertEquals('1', $activities->eq(0)->filter('.js_fill_id')->text());
        $this->assertEquals('91', $activities->eq(1)->filter('.js_fill_id')->text());
        $this->assertEquals('2', $activities->eq(2)->filter('.js_fill_id')->text());
        $this->assertEquals('55', $activities->eq(3)->filter('.js_fill_id')->text());
        $this->assertEquals('100', $activities->eq(4)->filter('.js_fill_id')->text());
    }

    public function testShowSuccessiveActivitiesInDifferentColors()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?id=1-2-3-4-5-6-7');
        $activities = $crawler->filter('.js_plan')->filter('.js_activity_block');

        $colorCode = $this->extractColorCode($activities->eq(0));
        for($i = 1; $i < $activities->count(); $i++) {
            $previousColorCode = $colorCode;
            $colorCode = $this->extractColorCode($activities->eq($i));
            
            $this->assertNotEquals($colorCode, $previousColorCode);
        }
    }

    /**
     * @param $activity
     * @return string
     */
    public function extractColorCode($activity)
    {
        $colorCodePrefix = ' bg';
        $classesString = $activity->attr('class');
        $colorCode = substr($classesString, strpos($classesString, $colorCodePrefix) + strlen($colorCodePrefix), 1);

        return $colorCode;
    }

    public function testShowAllActivitiesInPhase0LongUrl()
    {
        $client = static::createClient();
        $idsStringPhase0 = '1-2-3-18-22-31-32-36-42-43-46-52-59-70-76-81-82-84-85-90-106-107-108-114-122';
        $crawler = $client->request('GET', '/?id='.$idsStringPhase0.'&phase=0');
        $ids = explode('-', $idsStringPhase0);

        $activities = $crawler->filter('.js_plan')->filter('.js_activity_block');
        $this->assertEquals(count($ids), $activities->count());
        $this->assertEquals('3', $activities->eq(2)->filter('.js_fill_id')->text());
        $this->assertEquals('18', $activities->eq(3)->filter('.js_fill_id')->text());
        $this->assertEquals('22', $activities->eq(4)->filter('.js_fill_id')->text());
        $this->assertEquals('122', $activities->eq(24)->filter('.js_fill_id')->text());
    }

    public function testShowTitlePhase0LongUrl()
    {
        $client = static::createClient();
        $idsStringPhase0 = '1-2-3-18-22-31-32-36-42-43-46-52-59-70-76-81-82-84-85-90-106-107-108-114-122';
        $crawler = $client->request('GET', '/?id='.$idsStringPhase0.'&phase=0');

        $this->assertEquals('All activities for SET THE STAGE', $crawler->filter('.js_fill_plan_title')->text());
    }

    public function testShowTitlePhase1LongUrl()
    {
        $client = static::createClient();
        $idsStringPhase1 = '4-5-6-7-19-33-35-47-51-54-62-64-65-75-78-79-80-86-87-89-93-97-98-110-116-119-121-123';
        $crawler = $client->request('GET', '/?id='.$idsStringPhase1.'&phase=1');

        $this->assertEquals('All activities for GATHER DATA', $crawler->filter('.js_fill_plan_title')->text());
    }

    public function testRegressionAvoidUnlessNeededHeaderAllActivitiesFor()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=1-2-3&phase=0');
        $this->assertStringStartsWith('All activities for', $crawler->filter('.js_fill_plan_title')->text());

        $crawler = $client->request('GET', '/?id=1-2-3');
        $this->assertStringStartsNotWith('All activities for', $crawler->filter('.js_fill_plan_title')->text());
    }

    public function testHideSteppersPhase0LongUrl()
    {
        $client = static::createClient();

        // must not be hidden when phase is not specified in URL
        $idsStringPhase0 = '1-2-3-18-22-31-32-36-42-43-46-52-59-70-76-81-82-84-85-90-106-107-108-114-122';
        $crawler = $client->request('GET', '/?id='.$idsStringPhase0);
        $this->assertNotContains('hidden', $crawler->filter('.js_phase-stepper')->eq(0)->attr('class'));
        $this->assertNotContains('hidden', $crawler->filter('.js_phase-stepper')->eq(1)->attr('class'));

        // must be hidden when phase is specified in URL
        $idsStringPhase0 = '1-2-3-18-22-31-32-36-42-43-46-52-59-70-76-81-82-84-85-90-106-107-108-114-122';
        $crawler = $client->request('GET', '/?id='.$idsStringPhase0.'&phase=0');
        $this->assertContains('hidden', $crawler->filter('.js_phase-stepper')->eq(0)->attr('class'));
        $this->assertContains('hidden', $crawler->filter('.js_phase-stepper')->eq(1)->attr('class'));
    }

    public function testShowNumbersInFooter()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?id=3-87-113-13-16');
        $footer = $crawler->filter('.about')->filter('.content');
        $this->assertEquals('127', $footer->filter('.js_footer_no_of_activities')->text());
        $this->assertEquals('8349005', $footer->filter('.js_footer_no_of_combinations')->text());
        $this->assertEquals('25x30x22x22x23+5', $footer->filter('.js_footer_no_of_combinations_formula')->text());
    }

    public function testShowRandomPlanLinksOnHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals('Here are some random retrospective plans:', $crawler->filter('.js_fill_summary')->text());
    }

    public function testShowIdsInInputField()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?id=1-2-3-4-5');
        $this->assertEquals('1-2-3-4-5', $crawler->filter('.ids-display__input')->attr('value'));

        $crawler = $client->request('GET', '/?id=3-87-113-13-16');
        $this->assertEquals('3-87-113-13-16', $crawler->filter('.ids-display__input')->attr('value'));
    }

    public function testShowPhaseStepperNextSingleActivity()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=3');
        $this->assertEquals(
            '?id=18',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_next_button')->attr('href')
        );
    }

    public function testShowPhaseStepperPreviousSingleActivity()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=18');
        $this->assertEquals(
            '?id=3',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_prev_button')->attr('href')
        );
    }

    public function testShowPhaseStepperNextMultipleActivities()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/?id=3-87-113-13-16');
        $this->assertEquals(
            '?id=18-87-113-13-16',
            $crawler->filter('.js_activity_block')->eq(0)->filter('.js_next_button')->attr('href')
        );

        $this->assertEquals(
            '?id=3-89-113-13-16',
            $crawler->filter('.js_activity_block')->eq(1)->filter('.js_next_button')->attr('href')
        );
    }
}