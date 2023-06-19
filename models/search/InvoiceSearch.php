<?php

namespace app\models\search;

use sadi01\bidashboard\models\ReportWidget;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'price'], 'integer'],
            [['title', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }

    public function searchWidgetOld($params,$startDate,$endDate)
    {
        $query = Invoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['between', 'created_at', $startDate->format('Y-m-d - 00:00:00'), $endDate->format('Y-m-d - 23:23:59')]);

        $query->select([
            'SUM' => 'SUM(price)',
            'COUNT' => 'COUNT(id)',
        ]);

        return $dataProvider;
    }

    public function searchWidget3($params, $rangeType, $startRange, $endRange)
    {
        $query = Invoice::find();


        if ($rangeType == ReportWidget::RANGE_TYPE_MONTHLY) {
            $query->select([
                'total_count' => 'COUNT(' . Invoice::tableName() . '.id)',
                'total_amount' => 'SUM(' . Invoice::tableName() . '.price)',
                'year' => 'pyear(gdatestr(' . Invoice::tableName() . '.created_at))',
                'month' => 'pmonth(gdatestr(' . Invoice::tableName() . '.created_at))',
                'month_name' => 'pmonthname(gdatestr(' . Invoice::tableName() . '.created_at))',
            ]);
                $query->groupBy('pyear(gdatestr(' . Invoice::tableName() . '.created_at)), pmonth(gdatestr(' . Invoice::tableName() . '.created_at))')
                ->orderBy(Invoice::tableName() . '.created_at');
        }

        if ($rangeType == ReportWidget::RANGE_TYPE_DAILY) {
            $query->select([
                'total_count' => 'COUNT(' . Invoice::tableName() . '.id)',
                'total_amount' => 'SUM(' . Invoice::tableName() . '.price)',
                'year' => 'pyear(gdatestr(' . Invoice::tableName() . '.created_at))',
                'day' => 'pday(gdatestr(' . Invoice::tableName() . '.created_at))',
                'month' => 'pmonth(gdatestr(' . Invoice::tableName() . '.created_at))',
                'month_name' => 'pmonthname(gdatestr(' . Invoice::tableName() . '.created_at))',
            ]);
            $query->groupBy('pday(gdatestr(' . Invoice::tableName() . '.created_at)), pmonth(gdatestr(' . Invoice::tableName() . '.created_at)), pyear(gdatestr(' . Invoice::tableName() . '.created_at))')
            ->orderBy('gdatestr(' . Invoice::tableName() . '.created_at)');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        return $dataProvider;
    }
    public function searchWidget($params, $rangeType, $startRange, $endRange)
    {
        $query = Invoice::find();

        $query->andFilterWhere(['between', 'updated_at', $startRange, $endRange]);

        if ($rangeType == ReportWidget::RANGE_TYPE_MONTHLY) {
            $query->select([
                'total_count' => 'COUNT(' . Invoice::tableName() . '.id)',
                'total_amount' => 'SUM(' . Invoice::tableName() . '.price)',
                'year' => 'pyear(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
                'month' => 'pmonth(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
                'month_name' => 'pmonthname(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
            ]);
            $query
                ->groupBy('pyear(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at)), pmonth(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))')
                ->orderBy(Invoice::tableName() . '.updated_at');
        }

        if ($rangeType == ReportWidget::RANGE_TYPE_DAILY) {
            $query->select([
                'total_count' => 'COUNT(' . Invoice::tableName() . '.id)',
                'total_amount' => 'SUM(' . Invoice::tableName() . '.price)',
                'year' => 'pyear(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
                'day' => 'pday(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
                'month' => 'pmonth(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
                'month_name' => 'pmonthname(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))',
            ]);
            $query
                ->groupBy('pday(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at)), pmonth(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at)), pyear(FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at))')
                ->orderBy('FROM_UNIXTIME(' . Invoice::tableName() . '.updated_at)');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }
}
