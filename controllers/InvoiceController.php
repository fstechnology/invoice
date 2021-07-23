<?php

namespace app\controllers;

use app\models\InvoiceItem;
use app\models\Item;
use app\models\ShippingAddress;
use DateTime;
use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\base\BaseObject;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->getInvoiceItemById();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
        $modelShippingAddress = new ShippingAddress();
        $shippingAddress = $modelShippingAddress->getAll();

        if ($model->load(Yii::$app->request->post())) {

            $model->created_at = date('Y-m-d H:i:s');
            $issueDate = DateTime::createFromFormat('d/m/Y', $model->issue_date);
            $model->issue_date = $issueDate->format("Y-m-d");
            $dueDate = DateTime::createFromFormat('d/m/Y', $model->due_date);
            $model->due_date = $dueDate->format("Y-m-d");
            $model->total_amount = 0;
            $model->payment_status = 0;

            $totalAmount = 0;
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    foreach ($model->InvItems as $invItem) {
                        $invoiceItem =new InvoiceItem();
                        $invoiceItem->invoice_id = $model->id;
                        $invoiceItem->item_id = $invItem["item_id"];


                        $item = Item::findOne($invoiceItem->item_id);
                        if ($item == null) {
                            $transaction->rollBack();
                            break;
                        }
                        $invoiceItem->quantity = $invItem["quantity"];
                        $invoiceItem->unit_price = $item->price;
                        $invoiceItem->total_amount = $invoiceItem->unit_price * $invoiceItem->quantity;

                        if (! ($flag = $invoiceItem->save(false))) {
                            $transaction->rollBack();
                            break;
                        }else{
                            $totalAmount += $invoiceItem->total_amount;
                        }
                    }

                    $model->tax_amount = ($model->sub_total * 10) / 100;
                    $model->total_amount = $model->sub_total + $model->tax_amount;

                    $paymentStatus = Invoice::$PAYMENT_STATUS_UNPAID;
                    if ($model->payment >= $model->total_amount) {
                        $paymentStatus = Invoice::$PAYMENT_STATUS_PAID;
                    }
                    $model->payment_status = $paymentStatus;
                    $model->save(false);
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Invoice created');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'error on insert');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                $model->id = '';
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->getInvoiceItemById();
        $modelShippingAddress = new ShippingAddress();
        $shippingAddress = $modelShippingAddress->getAll();

        if ($model->load(Yii::$app->request->post())) {

            $model->created_at = date('Y-m-d H:i:s');
            /*$issueDate = DateTime::createFromFormat('d/m/Y', $model->issue_date);
            $model->issue_date = $issueDate->format("Y-m-d");
            $dueDate = DateTime::createFromFormat('d/m/Y', $model->due_date);
            $model->due_date = $dueDate->format("Y-m-d");*/
            $model->total_amount = 0;
            $model->payment_status = 0;

            $totalAmount = 0;
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    InvoiceItem::deleteAll(['invoice_id' => $id]);
                    foreach ($model->InvItems as $invItem) {
                        $invoiceItem =new InvoiceItem();
                        $invoiceItem->invoice_id = $model->id;
                        $invoiceItem->item_id = $invItem["item_id"];

                        $item = Item::findOne($invoiceItem->item_id);
                        if ($item == null) {
                            $transaction->rollBack();
                            break;
                        }
                        $invoiceItem->quantity = $invItem["quantity"];
                        $invoiceItem->unit_price = $item->price;
                        $invoiceItem->total_amount = $invoiceItem->unit_price * $invoiceItem->quantity;

                        if (! ($flag = $invoiceItem->save(false))) {
                            $transaction->rollBack();
                            break;
                        }else{
                            $totalAmount += $invoiceItem->total_amount;
                        }
                    }

                    $model->tax_amount = ($model->sub_total * 10) / 100;
                    $model->total_amount = $model->sub_total + $model->tax_amount;

                    $paymentStatus = Invoice::$PAYMENT_STATUS_UNPAID;
                    if ($model->payment >= $model->total_amount) {
                        $paymentStatus = Invoice::$PAYMENT_STATUS_PAID;
                    }
                    $model->payment_status = $paymentStatus;
                    $model->save(false);
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Invoice updated');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'error on update');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                $model->id = '';
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Lists all API.
     * @return mixed
     */
    public function actionApiList()
    {
        $models = Invoice::find()->all();
        $ids = [];
        foreach ($models as $model) {
            array_push($ids, $model->id);
        }

        return $this->render('api-list', [
            'ids' => $ids,
        ]);
    }

    public function actionGetAll()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $resp = [
            "code" => 500,
            "message" => "failed",
            "data" => null,
            "count" => 0,
        ];

        try {
            $models = Invoice::find()->all();

            $invoices = [];
            foreach ($models as $model) {
                $model->getInvoiceItemById();
                $dataShippingAddressFrom = $model->shippingAddressFrom;
                $dataShippingAddressFor = $model->shippingAddressFor;
                $items = $model->InvItems;

                $data = [
                    "invoice_id" => $model->id,
                    "subject" => $model->subject,
                    "issue_date" => $model->issue_date,
                    "due_date" => $model->due_date,
                    "shipping_address_from" => $dataShippingAddressFrom,
                    "shipping_address_for" => $dataShippingAddressFor,
                    "items" => $items,
                    "sub_total" => $model->sub_total,
                    "tax_amount" => $model->tax_amount,
                    "payment" => $model->payment,
                    "amount_due" => $model->amount_due,
                    "payment_status" => ($model->payment_status == 2) ? "PAID" : "UNPAID",
                    "created_at" => $model->created_at,
                ];

                array_push($invoices, $data);
            }

            $resp["data"] = $invoices;
            $resp["count"] = count($invoices);
            $resp["code"] = 200;
            $resp["message"] = "get invoice successfully";
        } catch (\Exception $e) {
            $resp["data"] = null;
            $resp["count"] = 0;
            $resp["code"] = 500;
            $resp["message"] = $e->getMessage();
        }
        return $resp;
    }

    public function actionGetById($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $idInt = 0;
        $resp = [
            "code" => 500,
            "message" => "failed",
            "data"=> null,
        ];

        try {
            if ($id == null) {
                throw new \Exception("id required");
            }

            $idInt = (int)$id;
            if (!is_int($idInt)) {
                throw new \Exception("id must be integer");
            }

            if ($idInt < 1) {
                throw new \Exception("id must be greeter than zero");
            }

            $model = Invoice::findOne($idInt);
            $model->getInvoiceItemById();
            $dataShippingAddressFrom = $model->shippingAddressFrom;
            $dataShippingAddressFor = $model->shippingAddressFor;
            $items = $model->InvItems;

            $data = [
                "invoice_id" => $model->id,
                "subject" => $model->subject,
                "issue_date" => $model->issue_date,
                "due_date" => $model->due_date,
                "shipping_address_from" => $dataShippingAddressFrom,
                "shipping_address_for" => $dataShippingAddressFor,
                "items" => $items,
                "sub_total" => $model->sub_total,
                "tax_amount" => $model->tax_amount,
                "payment" => $model->payment,
                "amount_due" => $model->amount_due,
                "payment_status" => ($model->payment_status == 2) ? "PAID" : "UNPAID",
                "created_at" => $model->created_at,
            ];

            $resp["data"] = $data;
            $resp["code"] = 200;
            $resp["message"] = "get invoice by id successfully";
        } catch (\Exception $e) {
            $resp["data"] = null;
            $resp["code"] = 500;
            $resp["message"] = $e->getMessage();
        }

        return $resp;
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
