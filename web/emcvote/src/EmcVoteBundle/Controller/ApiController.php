<?php

namespace EmcVoteBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  authentication=false,
     *  output="EmcVoteBundle\Entity\Campaign"
     * )
     *
     * @Annotations\View()
     *
     * @return  array
     */
    public function cgetCampaignsAction()
    {
        $campaigns = $this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT e FROM EmcVoteBundle:Campaign e LEFT JOIN EmcVoteBundle:Votes v WITH v.campaignId = e.campaignId WHERE e.start < CURRENT_TIMESTAMP()')
            ->getResult();
        return $campaigns;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  authentication=false,
     *  output="EmcVoteBundle\Entity\Candidate"
     * )
     * @Annotations\View()
     * @Annotations\QueryParam(
     *  name="campaignId",
     *  requirements="\d+",
     * )
     *
     * @param $campaignId
     * @return array|\EmcVoteBundle\Entity\Candidate[]
     */
    public function cgetCandidatesAction($campaignId)
    {
        $conn = $this->getDoctrine()->getConnection();

        $sql = "SELECT
	count(v.toID) as voices,
	FORMAT(count(v.toID) * 100 / c.Ballots, 2) as percents,
	ct.ToName as name,
	ct.ToDesc as description,
	ct.ToAddr as addr,
	ct.ToID as to_id
	from CampTo as ct
	left outer join Camp as c on ct.CampID = c.CampID
	left outer join Votes as v on ct.ToID = v.ToID and ct.CampID = v.CampID
	where ct.CampID = ?
	group by ct.ToAddr";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $campaignId);
        $stmt->execute();
        $candidates = $stmt->fetchAll();


//        $candidates = $this->getDoctrine()->getRepository('EmcVoteBundle:Candidate')->findBy(
//            ['campaignId' => $campaignId]
//        );

        return $candidates;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  authentication=false,
     *  output="EmcVoteBundle\Entity\Votes"
     * )
     *
     * @Annotations\View()
     *
     * @Annotations\QueryParam(
     *  name="campaignId",
     *  requirements="\d+",
     * )
     * @Annotations\QueryParam(
     *  name="candidateId",
     *  requirements="\d+",
     * )
     *
     * @param $candidateId
     * @param $campaignId
     *
     * @return array|\EmcVoteBundle\Entity\Votes[]
     */
    public function cgetVotesAction($candidateId, $campaignId)
    {
        return $this
            ->getDoctrine()
            ->getRepository('EmcVoteBundle:Votes')
            ->findBy(
                [
                    'toid' => $candidateId,
                    'campaignId' => $campaignId,
                ]
            );
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  authentication=false,
     * )
     *
     * @Annotations\View()
     *
     * @Annotations\QueryParam(
     *  name="campaignId",
     *  requirements="\d+",
     * )
     *
     * @param $campaignId
     *
     * @return array|\EmcVoteBundle\Entity\Votes[]
     */
    public function cgetNotVotedAction($campaignId)
    {
        return $this->getDoctrine()->getRepository('EmcVoteBundle:Votes')->findBy(['campaignId' => $campaignId, 'toid' => 0]);
    }
}
