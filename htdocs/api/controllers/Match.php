<?php

namespace controllers;

require_once './module/MatchModule.php';

class Match {

    protected $module;

    public function __construct()
    {
        $this->module = new \MatchModule();
    }

    public function index() {
    }

    public function create($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("base" == $target) {
            return $this->createBase();
        } else if ("team" == $target) {
            return $this->createTeam();
        } else if ("result" == $target) {
            return $this->createResult();
        }
    }

    public function result($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("team" == $target) {
            return $this->getTeamList();
        } else if ("detail" == $target) {
            return $this->getDetailList();
        } else if ("list" == $target) {
            //return $this->module->getResultList();
            return $this->getResultList();
        }
    }

    private function createBase() {
        // 試合結果（base）の登録
        //$params = json_decode(file_get_contents('php://input'), true);
        $params = $_GET;
        //var_dump($params);
        return array("params" => $params,
                     "result" => "success");
    }

    private function createTeam() {
        // 試合結果（team）の登録
        return array(
            "params" => $_GET,
            "result" => "Create team success");
    }

    private function createResult() {
        // 試合結果（team）の登録
        return array(
            "params" => $_GET,
            "result" => "Create result success");
    }

    private function getBaseList() {
        // 試合結果（team）の登録
        return array("result" => "Get list base success");
    }

    private function getTeamList() {
        // 試合結果（team）の登録
        return array("result" => "Get list team success");
    }

    private function getDetailList() {

    }

    private function getResultList() {
        // 試合結果（team）の登録
        return json_decode('{
            "results" : [
    {
        "year" : "平成２７年度（２０１５）",
      "grade" : "６年生（８期）　18名",
      "match" : [
        {
            "name" : "船橋選手権・予選",
          "schedule" : "4/7〜7/18",
          "venue" : "",
          "note" : "",
          "teamResult" : [
            {
                "teamName" : "八栄ＦＣ",
              "result" : [
                {
                    "rival" : "八栄ＦＣ",
                  "score" : "-",
                  "mark" : ""
                },
                {
                    "rival" : "咲が丘ＳＣ",
                  "score": "1 - 0",
                  "mark" : "○"
                },
                {
                    "rival" : "ＫＳＣブルー",
                  "score": "5 - 0",
                  "mark" : "○"
                },
                {
                    "rival" : "中野木ＦＣ　ビクトリー",
                  "score": "0 - 1",
                  "mark" : "●"
                },
                {
                    "rival" : "行田西ＦＣ",
                  "score": "2 - 1",
                  "mark" : "○"
                }
                ]
            },
            {
                "teamName" : "咲が丘ＳＣ",
              "result" : [
                {
                    "rival" : "八栄ＦＣ",
                  "score" : "0 - 1",
                  "mark" : "●"
                },
                {
                    "rival" : "咲が丘ＳＣ",
                  "score": "-",
                  "mark" : ""
                },
                {
                    "rival" : "ＫＳＣブルー",
                  "score": "1 - 1",
                  "mark" : "△"
                },
                {
                    "rival" : "中野木ＦＣ　ビクトリー",
                  "score": "3 - 0",
                  "mark" : "○"
                },
                {
                    "rival" : "行田西ＦＣ",
                  "score": "1 - 3",
                  "mark" : "●"
                }
              ]
            },
            {
                "teamName" : "ＫＳＣブルー",
              "result" : [
                {
                    "rival" : "八栄ＦＣ",
                  "score": "0 - 5",
                  "mark" : "●"
                },
                {
                    "rival" : "咲が丘ＳＣ",
                  "score": "1 - 1",
                  "mark" : "△"
                },
                {
                    "rival" : "ＫＳＣブルー",
                  "score" : "-",
                  "mark" : ""
                },
                {
                    "rival" : "中野木ＦＣ　ビクトリー",
                  "score": "1 - 0",
                  "mark" : "○"
                },
                {
                    "rival" : "行田西ＦＣ",
                  "score": "1 - 0",
                  "mark" : "○"
                }
              ]
            },
            {
                "teamName" : "中野木ＦＣ　ビクトリー",
              "result" : [
                {
                    "rival" : "八栄ＦＣ",
                  "score" : "1 - 0",
                  "mark" : "○"
                },
                {
                    "rival" : "咲が丘ＳＣ",
                  "score": "0 - 3",
                  "mark" : "●"
                },
                {
                    "rival" : "ＫＳＣブルー",
                  "score": "0 - 1",
                  "mark" : "●"
                },
                {
                    "rival" : "中野木ＦＣ　ビクトリー",
                  "score" : "-",
                  "mark" : ""
                },
                {
                    "rival" : "行田西ＦＣ",
                  "score": "0 - 0",
                  "mark" : "△"
                }
              ]
            },
            {
                "teamName" : "行田西ＦＣ",
              "result" : [
                {
                    "rival" : "八栄ＦＣ",
                  "score" : "1 - 2",
                  "mark" : "●"
                },
                {
                    "rival" : "咲が丘ＳＣ",
                  "score": "3 - 1",
                  "mark" : "○"
                },
                {
                    "rival" : "ＫＳＣブルー",
                  "score": "0 - 1",
                  "mark" : "●"
                },
                {
                    "rival" : "中野木ＦＣ　ビクトリー",
                  "score": "0 - 0",
                  "mark" : "△"
                },
                {
                    "rival" : "行田西ＦＣ",
                  "score" : "-",
                  "mark" : ""
                }
              ]
            }
          ]
        },
        {
            "name" : "船橋選手権・予選2",
            "schedule" : "4/7〜7/18",
            "venue" : "",
            "note" : "",
            "teamResult" : [
              {
                  "teamName" : "八栄ＦＣ",
                "result" : [
                  {
                      "rival" : "八栄ＦＣ",
                    "score" : "-",
                    "mark" : ""
                  },
                  {
                      "rival" : "咲が丘ＳＣ",
                    "score": "1 - 0",
                    "mark" : "○"
                  },
                  {
                      "rival" : "ＫＳＣブルー",
                    "score": "5 - 0",
                    "mark" : "○"
                  },
                  {
                      "rival" : "中野木ＦＣ　ビクトリー",
                    "score": "0 - 1",
                    "mark" : "●"
                  },
                  {
                      "rival" : "行田西ＦＣ",
                    "score": "2 - 1",
                    "mark" : "○"
                  }
                ]
              },
              {
                  "teamName" : "咲が丘ＳＣ",
                "result" : [
                  {
                      "rival" : "八栄ＦＣ",
                    "score" : "0 - 1",
                    "mark" : "●"
                  },
                  {
                      "rival" : "咲が丘ＳＣ",
                    "score": "-",
                    "mark" : ""
                  },
                  {
                      "rival" : "ＫＳＣブルー",
                    "score": "1 - 1",
                    "mark" : "△"
                  },
                  {
                      "rival" : "中野木ＦＣ　ビクトリー",
                    "score": "3 - 0",
                    "mark" : "○"
                  },
                  {
                      "rival" : "行田西ＦＣ",
                    "score": "1 - 3",
                    "mark" : "●"
                  }
                ]
              },
              {
                  "teamName" : "ＫＳＣブルー",
                "result" : [
                  {
                      "rival" : "八栄ＦＣ",
                    "score": "0 - 5",
                    "mark" : "●"
                  },
                  {
                      "rival" : "咲が丘ＳＣ",
                    "score": "1 - 1",
                    "mark" : "△"
                  },
                  {
                      "rival" : "ＫＳＣブルー",
                    "score" : "-",
                    "mark" : ""
                  },
                  {
                      "rival" : "中野木ＦＣ　ビクトリー",
                    "score": "1 - 0",
                    "mark" : "○"
                  },
                  {
                      "rival" : "行田西ＦＣ",
                    "score": "1 - 0",
                    "mark" : "○"
                  }
                ]
              },
              {
                  "teamName" : "中野木ＦＣ　ビクトリー",
                "result" : [
                  {
                      "rival" : "八栄ＦＣ",
                    "score" : "1 - 0",
                    "mark" : "○"
                  },
                  {
                      "rival" : "咲が丘ＳＣ",
                    "score": "0 - 3",
                    "mark" : "●"
                  },
                  {
                      "rival" : "ＫＳＣブルー",
                    "score": "0 - 1",
                    "mark" : "●"
                  },
                  {
                      "rival" : "中野木ＦＣ　ビクトリー",
                    "score" : "-",
                    "mark" : ""
                  },
                  {
                      "rival" : "行田西ＦＣ",
                    "score": "0 - 0",
                    "mark" : "△"
                  }
                ]
              },
              {
                  "teamName" : "行田西ＦＣ",
                "result" : [
                  {
                      "rival" : "八栄ＦＣ",
                    "score" : "1 - 2",
                    "mark" : "●"
                  },
                  {
                      "rival" : "咲が丘ＳＣ",
                    "score": "3 - 1",
                    "mark" : "○"
                  },
                  {
                      "rival" : "ＫＳＣブルー",
                    "score": "0 - 1",
                    "mark" : "●"
                  },
                  {
                      "rival" : "中野木ＦＣ　ビクトリー",
                    "score": "0 - 0",
                    "mark" : "△"
                  },
                  {
                      "rival" : "行田西ＦＣ",
                    "score" : "-",
                    "mark" : ""
                  }
                ]
              }
            ]
        }
      ]
    }
  ]
}');
    }
}