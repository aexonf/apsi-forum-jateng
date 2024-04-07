import React from "react";
import { Pen, LogOut, MessageSquare } from "lucide-react";
import Layout from "@/components/elements/layout";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";

const options = [
    {
        value: "terbaru",
        label: "Terbaru",
    },
    {
        value: "terlama",
        label: "Terlama",
    },
];

export default function Home() {
    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Profil
                </h3>
            </div>
            <div className="w-full">
                <Card>
                    <CardHeader />
                    <CardContent className="flex justify-center items-center flex-col">
                        <div className="relative flex">
                            <div className="size-40 bg-muted rounded-full" />
                            <Button
                                variant="ghost"
                                size="icon"
                                className="absolute right-0 bottom-0"
                            >
                                <Pen className="text-primary" />
                            </Button>
                        </div>
                        <div className="my-4 flex items-center flex-col">
                            <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                Adi Robi S.Pd
                            </h4>
                            <h5 className="scroll-m-20 text-lg font-semibold tracking-tight">
                                Kepala Sekolah SD Malangan
                            </h5>
                        </div>
                    </CardContent>
                    <CardFooter className="flex-col items-start">
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                NIP
                            </h4>
                            <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                12345
                            </h4>
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                Email
                            </h4>
                            <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                example@mail.com
                            </h4>
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                No. HP
                            </h4>
                            <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                0821234
                            </h4>
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                Tingkat
                            </h4>
                            <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                SD
                            </h4>
                        </div>
                    </CardFooter>
                </Card>
            </div>
            <Button className="w-full my-4 gap-4" variant="destructive" size="lg">
                <LogOut className="size-5" strokeWidth={3} />
                <span className="font-semibold"> 
                    Keluar
                </span>
            </Button>
        </Layout>
    );
}
