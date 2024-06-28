<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\PartialPayment;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use App\Traits\PaymentsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaxInvestigationAssessmentPaymentController extends Controller
{
    use PaymentsTrait;
    public function index()
    {
        if (!Gate::allows('tax-investigation-assessment-view')) {
            abort(403);
        }
        return view('investigation.assessment-payments.index');
    }

    public function show($id)
    {
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        try {
            $decryptedId = decrypt($id);

            // Retrieve partial payment with related tax assessment
            $partialPayment = PartialPayment::with('taxAssessment')->findOrFail($decryptedId);

            // Extract tax assessment and subject from the retrieved data
            $taxAssessment = $partialPayment->taxAssessment;
            $subject = $taxAssessment->assessment_type::findOrFail($taxAssessment->assessment_id);
            // $subject = TaxAudit::findOrFail($taxAssessment->assessment_id);

            // Retrieve tax assessments for the subject
            $taxAssessments = TaxAssessment::where('assessment_id', $subject->id)
                ->where('assessment_type', get_class($subject))
                ->get();

            return view('investigation.assessment-payments.preview', compact('subject', 'taxAssessments', 'partialPayment'));
        } catch (\Exception $e) {

            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e,
            ]);

            return redirect()->back()->withError('Something went wrong Please contact your admin');
        }
    }

    public function approveReject(Request $request, $paymentId)
    {
        $request->validate([
            'comments' => 'required|string',
            'action' => 'required|in:approve,reject',
        ]);

        DB::beginTransaction();

        try {
            $partialPayment = PartialPayment::findOrFail(decrypt($paymentId));

            if ($request->action === 'approve') {
                // Perform approval logic
                $partialPayment->status = 'approved';
                $partialPayment->comments = $request->comments;
                $partialPayment->save();


                // Generate control number
                $this->generatePartialPaymentControlNo($partialPayment);
                DB::commit();
                return redirect()->back()->with('success', 'Assessment approved and control number generated.');
            } else {
                // Perform rejection logic
                $partialPayment->status = 'rejected';
                $partialPayment->comments = $request->comments;
                $partialPayment->save();

                DB::commit();
                return redirect()->back()->with('success', 'Assessment Payments rejected.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Return an error response
            return redirect()->back()->withError('Something went wrong Please contact your admin');
        }
    }
}
