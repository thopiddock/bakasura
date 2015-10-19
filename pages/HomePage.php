<?php

class HomePage implements IPage
{
    /**
     * Get the title of the page.
     * @return string
     */
    function getTitle()
    {
        return 'Home Page';
    }

    /**
     * Get the pages short name, as used to define it's URL.
     * i.e. Return 'about' for http://example.com/about
     * @return mixed
     */
    function getShortName()
    {
        return 'home';
    }

    /**
     * Get the page summary.
     * @return string
     */
    function getSummary()
    {
        return "<p>I'm the home page</p>";
    }

    /**
     * Get the main content to display.
     * @return string
     */
    function getContent()
    {
        $content = "";

        if (Site::GetAuthenticator()->getAuthenticationLevel() == AuthenticationLevelEnum::Visitor)
        {
            $textSectionFragment = new TextSectionFragment("Sed (saepe enim redeo ad Scipionem, cuius omnis sermo erat de amicitia) querebatur, quod omnibus in rebus homines diligentiores essent; capras et oves quot quisque haberet, dicere posse, amicos quot haberet, non posse dicere et in illis quidem parandis adhibere curam, in amicis eligendis neglegentis esse nec habere quasi signa quaedam et notas, quibus eos qui ad amicitias essent idonei, iudicarent. Sunt igitur firmi et stabiles et constantes eligendi; cuius generis est magna penuria. Et iudicare difficile est sane nisi expertum; experiendum autem est in ipsa amicitia. Ita praecurrit amicitia iudicium tollitque experiendi potestatem.");
            $content             = $textSectionFragment->getContent() .
                "<p>Fieri, inquam, Triari, nullo pacto potest, ut non dicas, quid non probes eius, a quo dissentias. quid enim me prohiberet Epicureum esse, si probarem, quae ille diceret? cum praesertim illa perdiscere ludus esset. Quam ob rem dissentientium inter se reprehensiones non sunt vituperandae, maledicta, contumeliae, tum iracundiae, contentiones concertationesque in disputando pertinaces indignae philosophia mihi videri solent.</p>
<p>Inter has ruinarum varietates a Nisibi quam tuebatur accitus Vrsicinus, cui nos obsecuturos iunxerat imperiale praeceptum, dispicere litis exitialis certamina cogebatur abnuens et reclamans, adulatorum oblatrantibus turmis, bellicosus sane milesque semper et militum ductor sed forensibus iurgiis longe discretus, qui metu sui discriminis anxius cum accusatores quaesitoresque subditivos sibi consociatos ex isdem foveis cerneret emergentes, quae clam palamve agitabantur, occultis Constantium litteris edocebat inplorans subsidia, quorum metu tumor notissimus Caesaris exhalaret.</p>
<p>Auxerunt haec vulgi sordidioris audaciam, quod cum ingravesceret penuria commeatuum, famis et furoris inpulsu Eubuli cuiusdam inter suos clari domum ambitiosam ignibus subditis inflammavit rectoremque ut sibi iudicio imperiali addictum calcibus incessens et pugnis conculcans seminecem laniatu miserando discerpsit. post cuius lacrimosum interitum in unius exitio quisque imaginem periculi sui considerans documento recenti similia formidabat.</p>
<p>Quam ob rem cave Catoni anteponas ne istum quidem ipsum, quem Apollo, ut ais, sapientissimum iudicavit; huius enim facta, illius dicta laudantur. De me autem, ut iam cum utroque vestrum loquar, sic habetote.</p>";
        }

        return $content;
    }

    /**
     * Get the style sheets for this page.
     * @return string | array
     */
    function getStyleSheets()
    {
        return null;
    }

    /**
     * Get the scripts for this page.
     * @return string | array
     */
    function getScripts()
    {
        return null;
    }
}